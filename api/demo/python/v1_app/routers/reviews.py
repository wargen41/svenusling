from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session
from app.models import User
from app.schemas import ReviewCreate, ReviewUpdate, ReviewResponse
from app.crud.review import ReviewCRUD
from app.crud.movie import MovieCRUD
from app.database import get_db
from app.dependencies import get_current_user

router = APIRouter(prefix="/api/reviews", tags=["reviews"])

@router.post("", response_model=dict, status_code=status.HTTP_201_CREATED)
async def create_review(
    review: ReviewCreate,
    current_user: User = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    """Create a new review (authenticated users only)"""
    
    # Check if movie exists
    if not MovieCRUD.get_movie_by_id(db, review.movie_id):
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Movie not found"
        )
    
    # Check if user already reviewed this movie
    if ReviewCRUD.user_already_reviewed(db, review.movie_id, current_user.id):
        raise HTTPException(
            status_code=status.HTTP_409_CONFLICT,
            detail="You have already reviewed this movie"
        )
    
    db_review = ReviewCRUD.create_review(db, review, current_user.id)
    
    return {
        "success": True,
        "message": "Review created",
        "id": db_review.id
    }

@router.put("/{review_id}", response_model=dict)
async def update_review(
    review_id: int,
    review_update: ReviewUpdate,
    current_user: User = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    """Update your own review"""
    
    review = ReviewCRUD.get_review_by_id(db, review_id)
    
    if not review:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Review not found"
        )
    
    # Check authorization
    if review.user_id != current_user.id:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="You can only update your own reviews"
        )
    
    ReviewCRUD.update_review(db, review_id, review_update)
    
    return {
        "success": True,
        "message": "Review updated"
    }

@router.delete("/{review_id}", response_model=dict)
async def delete_review(
    review_id: int,
    current_user: User = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    """Delete your own review"""
    
    review = ReviewCRUD.get_review_by_id(db, review_id)
    
    if not review:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Review not found"
        )
    
    # Check authorization
    if review.user_id != current_user.id:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="You can only delete your own reviews"
        )
    
    ReviewCRUD.delete_review(db, review_id)
    
    return {
        "success": True,
        "message": "Review deleted"
    }