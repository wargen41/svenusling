<?php
namespace App\Controllers;

use App\Config\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PersonController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Public: List persons with filtering
     */
    public function listPersons(Request $request, Response $response): Response
    {
        try {
            $params = $request->getQueryParams();
            $category = $params['category'] ?? null;  // actor, director, voice
            $search = $params['search'] ?? null;
            $skip = (int)($params['skip'] ?? 0);
            $limit = min((int)($params['limit'] ?? 10), 100);

            $where = [];
            $bindings = [];

            if ($category) {
                $where[] = 'category = ?';
                $bindings[] = $category;
            }

            if ($search) {
                $where[] = 'name LIKE ?';
                $bindings[] = '%' . $search . '%';
            }

            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

            $sql = "
                SELECT id, category, name, birth_date, death_date, poster_image_id
                FROM persons
                $whereClause
                ORDER BY name ASC
                LIMIT ? OFFSET ?
            ";

            $stmt = $this->db->prepare($sql);
            $bindings[] = $limit;
            $bindings[] = $skip;
            $stmt->execute($bindings);
            $persons = $stmt->fetchAll();

            // Get total count
            $countSql = "SELECT COUNT(*) as count FROM persons $whereClause";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute(array_slice($bindings, 0, -2));
            $countResult = $countStmt->fetch();
            $total = $countResult['count'];

            return $this->jsonResponse($response, [
                'success' => true,
                'data' => $persons,
                'pagination' => [
                    'count' => count($persons),
                    'total' => $total,
                    'skip' => $skip,
                    'limit' => $limit
                ]
            ]);

        } catch (\Exception $e) {
            error_log('Error in listPersons: ' . $e->getMessage());
            return $this->jsonResponse($response, ['error' => 'Failed to fetch persons'], 500);
        }
    }

    /**
     * Public: Get single person with all related data
     */
    public function getPerson(Request $request, Response $response, array $args): Response
    {
        try {
            $personId = $args['id'] ?? null;

            if (!$personId || !is_numeric($personId)) {
                return $this->jsonResponse($response, ['error' => 'Invalid person ID'], 400);
            }

            // Get person
            $stmt = $this->db->prepare('SELECT * FROM persons WHERE id = ?');
            $stmt->execute([$personId]);
            $person = $stmt->fetch();

            if (!$person) {
                return $this->jsonResponse($response, ['error' => 'Person not found'], 404);
            }

            // Get movies (filmography)
            $stmt = $this->db->prepare('
                SELECT m.id, m.title, m.year, m.type, mp.category, mp.role_name
                FROM movies m
                JOIN movies_persons mp ON mp.movie_id = m.id
                WHERE mp.person_id = ?
                ORDER BY m.year DESC
            ');
            $stmt->execute([$personId]);
            $person['filmography'] = $stmt->fetchAll();

            // Get trivia
            $stmt = $this->db->prepare('SELECT sv, en FROM persons_trivia WHERE person_id = ?');
            $stmt->execute([$personId]);
            $person['trivia'] = $stmt->fetchAll();

            // Get relations
            $stmt = $this->db->prepare('
                SELECT r.id, r.sv, r.en, rp.person_2_id, rp.person_2_name, rp.date_1, rp.date_2
                FROM relations r
                JOIN relations_persons rp ON rp.relation_id = r.id
                WHERE rp.person_id = ?
            ');
            $stmt->execute([$personId]);
            $person['relations'] = $stmt->fetchAll();

            // Get poster image if referenced
            if ($person['poster_image_id']) {
                $stmt = $this->db->prepare('SELECT * FROM media WHERE id = ?');
                $stmt->execute([$person['poster_image_id']]);
                $person['poster_image'] = $stmt->fetch();
            }

            return $this->jsonResponse($response, [
                'success' => true,
                'data' => $person
            ]);

        } catch (\Exception $e) {
            error_log('Error in getPerson: ' . $e->getMessage());
            return $this->jsonResponse($response, ['error' => 'Failed to fetch person'], 500);
        }
    }

    /**
     * Protected: Create person (admin only)
     */
    public function createPerson(Request $request, Response $response): Response
    {
        try {
            $userRole = $request->getAttribute('user_role');

            if ($userRole !== 'admin') {
                return $this->jsonResponse($response, ['error' => 'Admin access required'], 403);
            }

            $data = $request->getParsedBody();

            if (empty($data['name']) || empty($data['category'])) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Name and category are required'],
                    422
                );
            }

            $stmt = $this->db->prepare('
                INSERT INTO persons (category, name, birth_date, death_date, poster_image_id)
                VALUES (?, ?, ?, ?, ?)
            ');

            $stmt->execute([
                $data['category'],
                $data['name'],
                $data['birth_date'] ?? null,
                $data['death_date'] ?? null,
                $data['poster_image_id'] ?? null
            ]);

            $personId = $this->db->lastInsertId();

            return $this->jsonResponse(
                $response,
                ['success' => true, 'message' => 'Person created', 'id' => (int)$personId],
                201
            );

        } catch (\Exception $e) {
            error_log('Error in createPerson: ' . $e->getMessage());
            return $this->jsonResponse($response, ['error' => 'Failed to create person'], 500);
        }
    }

    /**
     * Protected: Update person (admin only)
     */
    public function updatePerson(Request $request, Response $response, array $args): Response
    {
        try {
            $personId = $args['id'] ?? null;
            $userRole = $request->getAttribute('user_role');

            if ($userRole !== 'admin') {
                return $this->jsonResponse($response, ['error' => 'Admin access required'], 403);
            }

            $data = $request->getParsedBody();

            $stmt = $this->db->prepare('SELECT id FROM persons WHERE id = ?');
            $stmt->execute([$personId]);
            if (!$stmt->fetch()) {
                return $this->jsonResponse($response, ['error' => 'Person not found'], 404);
            }

            $updates = [];
            $bindings = [];
            $fields = ['category', 'name', 'birth_date', 'death_date', 'poster_image_id'];

            foreach ($fields as $field) {
                if (isset($data[$field])) {
                    $updates[] = "$field = ?";
                    $bindings[] = $data[$field];
                }
            }

            if (empty($updates)) {
                return $this->jsonResponse($response, ['error' => 'No fields to update'], 400);
            }

            $bindings[] = $personId;
            $sql = 'UPDATE persons SET ' . implode(', ', $updates) . ' WHERE id = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->execute($bindings);

            return $this->jsonResponse($response, ['success' => true, 'message' => 'Person updated']);

        } catch (\Exception $e) {
            error_log('Error in updatePerson: ' . $e->getMessage());
            return $this->jsonResponse($response, ['error' => 'Failed to update person'], 500);
        }
    }

    /**
     * Protected: Delete person (admin only)
     */
    public function deletePerson(Request $request, Response $response, array $args): Response
    {
        try {
            $personId = $args['id'] ?? null;
            $userRole = $request->getAttribute('user_role');

            if ($userRole !== 'admin') {
                return $this->jsonResponse($response, ['error' => 'Admin access required'], 403);
            }

            $stmt = $this->db->prepare('DELETE FROM persons WHERE id = ?');
            $stmt->execute([$personId]);

            if ($stmt->rowCount() === 0) {
                return $this->jsonResponse($response, ['error' => 'Person not found'], 404);
            }

            return $this->jsonResponse($response, ['success' => true, 'message' => 'Person deleted']);

        } catch (\Exception $e) {
            error_log('Error in deletePerson: ' . $e->getMessage());
            return $this->jsonResponse($response, ['error' => 'Failed to delete person'], 500);
        }
    }

    private function jsonResponse(Response $response, $data, $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}