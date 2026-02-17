from fastapi import APIRouter, Depends, HTTPException, status, Query
from sqlalchemy.orm import Session
from typing import List
from app.models import User
from app.schemas import MovieCreate, MovieUpdate, MovieResponse, MovieWithReviews, ReviewResponse
from app.crud.movie import MovieCRUD
from app.crud.review import ReviewCRUD
from app.database import get_db
from app.dependencies import get_current_user, get_admin_user

router = APIRouter(prefix="/api/movies", tags=["movies"])

@router.get("", response_model=List[MovieResponse])
async def list_movies(
    skip: int = Query(0, ge=0),
    limit: int = Query(10, ge=1, le=100),
    db: Session = Depends(get_db)
):
    """Get all movies (public endpoint)"""
    movies = MovieCRUD.get_all_movies(db, skip=skip, limit=limit)
    return movies

@router.get("/{movie_id}", response_model=MovieWithReviews)
async def get_movie(movie_id: int, db: Session = Depends(get_db)):
    """Get a single movie with its reviews (public endpoint)"""
    movie = MovieCRUD.get_movie_by_id(db, movie_id)
    
    if not movie:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Movie not found"
        )
    
    # Get reviews
    reviews = ReviewCRUD.get_movie_reviews(db, movie_id)
    movie.reviews = reviews
    
    return movie

@router.post("", response_model=dict, status_code=status.HTTP_201_CREATED)
async def create_movie(
    movie: MovieCreate,
    current_user: User = Depends(get_admin_user),
    db: Session = Depends(get_db)
):
    """Create a new movie (admin only)"""
    db_movie = MovieCRUD.create_movie(db, movie, current_user.id)
    
    return {
        "success": True,
        "message": "Movie created",
        "id": db_movie.id
    }

@router.put("/{movie_id}", response_model=dict)
async def update_movie(
    movie_id: int,
    movie_update: MovieUpdate,
    current_user: User = Depends(get_admin_user),
    db: Session = Depends(get_db)
):
    """Update a movie (admin only)"""
    movie = MovieCRUD.get_movie_by_id(db, movie_id)
    
    if not movie:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Movie not found"
        )
    
    updated_movie = MovieCRUD.update_movie(db, movie_id, movie_update)
    
    return {
        "success": True,
        "message": "Movie updated"
    }

@router.delete("/{movie_id}", response_model=dict)
async def delete_movie(
    movie_id: int,
    current_user: User = Depends(get_admin_user),
    db: Session = Depends(get_db)
):
    """Delete a movie (admin only)"""
    if not MovieCRUD.delete_movie(db, movie_id):
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Movie not found"
        )
    
    return {
        "success": True,
        "message": "Movie deleted"
    }