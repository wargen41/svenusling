from fastapi import APIRouter
from app.routers import auth, movies, reviews

# Create combined router
api_router = APIRouter()

# Include all sub-routers
api_router.include_router(auth.router)
api_router.include_router(movies.router)
api_router.include_router(reviews.router)