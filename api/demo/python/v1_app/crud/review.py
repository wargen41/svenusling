from sqlalchemy.orm import Session
from app.models import Review
from app.schemas import ReviewCreate, ReviewUpdate
from app.crud.movie import MovieCRUD

class ReviewCRUD:
    @staticmethod
    def get_review_by_id(db: Session, review_id: int) -> Review:
        return db.query(Review).filter(Review.id == review_id).first()
    
    @staticmethod
    def get_movie_reviews(db: Session, movie_id: int):
        """Get all reviews for a movie"""
        return db.query(Review).filter(Review.movie_id == movie_id).all()
    
    @staticmethod
    def user_already_reviewed(db: Session, movie_id: int, user_id: int) -> bool:
        """Check if user has already reviewed this movie"""
        return db.query(Review).filter(
            Review.movie_id == movie_id,
            Review.user_id == user_id
        ).first() is not None
    
    @staticmethod
    def create_review(db: Session, review: ReviewCreate, user_id: int) -> Review:
        """Create a new review"""
        db_review = Review(
            movie_id=review.movie_id,
            user_id=user_id,
            rating=review.rating,
            comment=review.comment
        )
        db.add(db_review)
        db.commit()
        db.refresh(db_review)
        
        # Update movie rating
        MovieCRUD.update_movie_rating(db, review.movie_id)
        
        return db_review
    
    @staticmethod
    def update_review(db: Session, review_id: int, review_update: ReviewUpdate) -> Review:
        """Update a review"""
        db_review = ReviewCRUD.get_review_by_id(db, review_id)
        
        if not db_review:
            return None
        
        update_data = review_update.model_dump(exclude_unset=True)
        for key, value in update_data.items():
            setattr(db_review, key, value)
        
        db.add(db_review)
        db.commit()
        db.refresh(db_review)
        
        # Update movie rating
        MovieCRUD.update_movie_rating(db, db_review.movie_id)
        
        return db_review
    
    @staticmethod
    def delete_review(db: Session, review_id: int) -> bool:
        """Delete a review"""
        db_review = ReviewCRUD.get_review_by_id(db, review_id)
        
        if not db_review:
            return False
        
        movie_id = db_review.movie_id
        db.delete(db_review)
        db.commit()
        
        # Update movie rating
        MovieCRUD.update_movie_rating(db, movie_id)
        
        return True