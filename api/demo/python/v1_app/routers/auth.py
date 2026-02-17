from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session
from datetime import timedelta
from app.schemas import UserRegister, UserLogin, TokenResponse, AuthResponse, UserResponse
from app.crud.user import UserCRUD
from app.security import SecurityUtils
from app.database import get_db
from app.config import settings

router = APIRouter(prefix="/api/auth", tags=["authentication"])

@router.post("/register", response_model=AuthResponse, status_code=status.HTTP_201_CREATED)
async def register(user: UserRegister, db: Session = Depends(get_db)):
    """Register a new user"""
    
    # Check if user already exists
    if UserCRUD.get_user_by_email(db, user.email):
        raise HTTPException(
            status_code=status.HTTP_409_CONFLICT,
            detail="Email already registered"
        )
    
    if UserCRUD.get_user_by_username(db, user.username):
        raise HTTPException(
            status_code=status.HTTP_409_CONFLICT,
            detail="Username already taken"
        )
    
    # Create user
    db_user = UserCRUD.create_user(db, user)
    
    # Generate token
    access_token_expires = timedelta(minutes=settings.access_token_expire_minutes)
    token = SecurityUtils.create_access_token(
        data={"sub": db_user.id, "role": db_user.role},
        expires_delta=access_token_expires
    )
    
    return AuthResponse(
        success=True,
        message="User registered successfully",
        token=token,
        user=UserResponse.model_validate(db_user)
    )

@router.post("/login", response_model=AuthResponse)
async def login(credentials: UserLogin, db: Session = Depends(get_db)):
    """Login user and get access token"""
    
    # Authenticate user
    user = UserCRUD.authenticate_user(db, credentials.email, credentials.password)
    
    if not user:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Invalid credentials"
        )
    
    # Generate token
    access_token_expires = timedelta(minutes=settings.access_token_expire_minutes)
    token = SecurityUtils.create_access_token(
        data={"sub": user.id, "role": user.role},
        expires_delta=access_token_expires
    )
    
    return AuthResponse(
        success=True,
        message="Login successful",
        token=token,
        user=UserResponse.model_validate(user)
    )