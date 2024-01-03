<style>
    .modern-section {
        background-color: #ffffff; /* White background */
        padding: 50px 0;
        text-align: center;
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .button-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }
    
    .modern-button {
        background-color: #482683; /* Bootstrap primary blue */
        color: #ffffff; /* White text */
        border: 2px solid transparent;
        padding: 15px 30px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    /* Hover effect for modern buttons */
    .modern-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
        border-color: #482683; /* Darker blue */
        color: #482683; /* Darker blue */
    }
    
    /* Adding icons to the buttons */
    .modern-button i {
        font-size: 24px;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .button-container {
            flex-direction: column;
        }
    
        .modern-button {
            width: 80%;
            margin: 0 auto;
            margin-bottom: 15px;
        }
    }
    
    /* Title style */
    .modern-title {
        color: #482683; /* Darker text for better contrast on white */
        margin-bottom: 40px;
    }
    </style>
    
    <div class="modern-section">
        <div class="container">
            <h1 class="modern-title fw-black">Nos Services</h1> 
            <div class="button-container">
                <button class="modern-button" onclick="location.href='#'">
                    <i class="fas fa-map-marker-alt"></i> 
                    Determine Section
                </button>
                <button class="modern-button" onclick="location.href='#'">
                    <i class="fas fa-question-circle"></i> 
                    Questions
                </button>
                <button class="modern-button" onclick="location.href='#'">
                    <i class="fas fa-gavel"></i> 
                    Mentions Légales
                </button>
                <button class="modern-button" onclick="location.href='#'">
                    <i class="fas fa-ellipsis-h"></i> 
                    Autre
                </button>
            </div>
        </div>
    </div>
    