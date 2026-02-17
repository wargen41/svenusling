import uvicorn
import os

if __name__ == "__main__":
    # Load environment variables
    os.environ.setdefault("ENVIRONMENT", "development")
    
    uvicorn.run(
        "app.main:app",
        host="0.0.0.0",
        port=8000,
        reload=True,
        log_level="info"
    )