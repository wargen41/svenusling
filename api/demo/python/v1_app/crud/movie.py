from sqlalchemy.orm import Session
from sqlalchemy import func
from app.models import Movie, Review
from app.schemas import MovieCreate, MovieUpdate

class MovieCRUD:
    @staticmethod
    def get_all_movies(db: Session, skip: int = 0, limit: int = 100):
        """Get all movies with pagination"""
        return db.query(Movie).offset(skip).limit(limit).all()
    
    @staticmethod
    def get_movie_by_id(db: Session, movie_id: int) -> Movie:
        return db.query(Movie).filter(Movie.id == movie_id).first()
    
    @staticmethod
    def create_movie(db: Session, movie: MovieCreate, user_id: int) -> Movie:
        """Create a new movie"""
        db_movie = Movie(
            title=movie.title,
            description=movie.description,
            year=movie.year,
            director=movie.director,
            created_by=user_id
        )
        db.add(db_movie)
        db.commit()
        db.refresh(db_movie)
        return db_movie
    
    @staticmethod
    def update_movie(db: Session, movie_id: int, movie_update: MovieUpdate) -> Movie:
        """Update a movie"""
        db_movie = MovieCRUD.get_movie_by_id(db, movie_id)
        
        if not db_movie:
            return None
        
        update_data = movie_update.model_dump(exclude_unset=True)
        for key, value in update_data.items():
            setattr(db_movie, key, value)
        
        db.add(db_movie)
        db.commit()
        db.refresh(db_movie)
        return db_movie
    
    @staticmethod
    def delete_movie(db: Session, movie_id: int) -> bool:
        """Delete a movie"""
        db_movie = MovieCRUD.get_movie_by_id(db, movie_id)
        
        if not db_movie:
            return False
        
        db.delete(db_movie)
        db.commit()
        return True
    
    @staticmethod
    def update_movie_rating(db: Session, movie_id: int):
        """Recalculate and update average rating for a movie"""
        avg_rating = db.query(func.avg(Review.rating)).filter(
            Review.movie_id == movie_id
        ).scalar()
        
        db_movie = MovieCRUD.get_movie_by_id(db, movie_id)
        if db_movie:
            db_movie.rating = avg_rating or 0.0
            db.add(db_movie)
            db.commit()