from pydantic import BaseModel, EmailStr, Field, validator
from typing import Optional, List
from datetime import datetime

# ==================== User Schemas ====================

class UserBase(BaseModel):
    username: str = Field(..., min_length=3, max_length=50)
    email: EmailStr

class UserRegister(UserBase):
    password: str = Field(..., min_length=8)
    
    @validator('password')
    def password_must_be_strong(cls, v):
        if not any(c.isupper() for c in v):
            raise ValueError('Password must contain at least one uppercase letter')
        return v

class UserLogin(BaseModel):
    email: EmailStr
    password: str

class UserResponse(UserBase):
    id: int
    role: str
    created_at: datetime
    
    class Config:
        from_attributes = True

class UserInDB(UserResponse):
    password_hash: str

# ==================== Movie Schemas ====================

class MovieBase(BaseModel):
    title: str = Field(..., min_length=2, max_length=255)
    description: Optional[str] = Field(None, max_length=2000)
    year: Optional[int] = Field(None, ge=1800, le=2100)
    director: Optional[str] = Field(None, max_length=255)

class MovieCreate(MovieBase):
    pass

class MovieUpdate(BaseModel):
    title: Optional[str] = Field(None, min_length=2, max_length=255)
    description: Optional[str] = Field(None, max_length=2000)
    year: Optional[int] = Field(None, ge=1800, le=2100)
    director: Optional[str] = Field(None, max_length=255)

class MovieResponse(MovieBase):
    id: int
    rating: float
    created_at: datetime
    updated_at: datetime
    
    class Config:
        from_attributes = True

class MovieWithReviews(MovieResponse):
    reviews: List['ReviewResponse'] = []

# ==================== Review Schemas ====================

class ReviewBase(BaseModel):
    movie_id: int
    rating: float = Field(..., ge=1, le=10)
    comment: Optional[str] = Field(None, max_length=1000)

class ReviewCreate(ReviewBase):
    pass

class ReviewUpdate(BaseModel):
    rating: Optional[float] = Field(None, ge=1, le=10)
    comment: Optional[str] = Field(None, max_length=1000)

class ReviewResponse(BaseModel):
    id: int
    movie_id: int
    rating: float
    comment: Optional[str]
    created_at: datetime
    author: UserResponse
    
    class Config:
        from_attributes = True

class ReviewWithMovie(ReviewResponse):
    movie: MovieResponse

# ==================== Auth Schemas ====================

class TokenResponse(BaseModel):
    access_token: str
    token_type: str = "bearer"

class AuthResponse(BaseModel):
    success: bool
    message: str
    token: str
    user: UserResponse

# Update forward references for nested models
MovieWithReviews.model_rebuild()
ReviewResponse.model_rebuild()
ReviewWithMovie.model_rebuild()