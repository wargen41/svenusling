from pydantic_settings import BaseSettings
from typing import Optional

class Settings(BaseSettings):
    """Application settings"""
    
    # API
    api_title: str = "Movie Database API"
    api_version: str = "1.0.0"
    api_description: str = "A simple movie database API with reviews"
    
    # Database
    database_url: str = "sqlite:///./movies.db"
    
    # JWT
    secret_key: str = "your-secret-key-change-in-production"
    algorithm: str = "HS256"
    access_token_expire_minutes: int = 60
    
    # CORS
    allowed_origins: list = [
        "http://localhost:3000",
        "http://localhost:8000",
        "https://yourdomain.com"
    ]
    
    class Config:
        env_file = ".env"

settings = Settings()