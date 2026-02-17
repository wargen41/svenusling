from sqlalchemy.orm import Session
from app.models import User
from app.schemas import UserRegister
from app.security import SecurityUtils

class UserCRUD:
    @staticmethod
    def get_user_by_email(db: Session, email: str) -> User:
        return db.query(User).filter(User.email == email).first()
    
    @staticmethod
    def get_user_by_username(db: Session, username: str) -> User:
        return db.query(User).filter(User.username == username).first()
    
    @staticmethod
    def get_user_by_id(db: Session, user_id: int) -> User:
        return db.query(User).filter(User.id == user_id).first()
    
    @staticmethod
    def create_user(db: Session, user: UserRegister) -> User:
        """Create a new user"""
        db_user = User(
            username=user.username,
            email=user.email,
            password_hash=SecurityUtils.hash_password(user.password),
            role="user"
        )
        db.add(db_user)
        db.commit()
        db.refresh(db_user)
        return db_user
    
    @staticmethod
    def authenticate_user(db: Session, email: str, password: str) -> User:
        """Authenticate user with email and password"""
        user = UserCRUD.get_user_by_email(db, email)
        
        if not user:
            return None
        
        if not SecurityUtils.verify_password(password, user.password_hash):
            return None
        
        return user