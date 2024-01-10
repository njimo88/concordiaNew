<style>
    .modern-section {
        background-color: #ffffff;
        padding: 50px 0;
        text-align: center;
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .button-container {
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }
    
    .modern-button {
        color: #ffffff;
        border: none;
        padding: 15px 30px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 50px;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .modern-button:hover {
        animation: pulse 1s infinite;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        color: #ffffff !important;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }
    
    .modern-button:nth-child(1) {
        background-color: #20B2AA; /* Turquoise */
    }
    .modern-button:nth-child(1):hover {
        background-color: #1C948A; /* Darker Turquoise */
    }
    
    .modern-button:nth-child(2) {
        background-color: #00bfff; /* Deep Sky Blue */
    }
    .modern-button:nth-child(2):hover {
        background-color: #009fdd; /* Darker Deep Sky Blue */
    }
    
    .modern-button:nth-child(3) {
        background-color: #c8a2c8; /* Soft Lilac */
    }
    .modern-button:nth-child(3):hover {
        background-color: #b091b0; /* Darker Soft Lilac */
    }
    
    .modern-button:nth-child(4) {
        background-color: #708090; /* Slate Gray */
    }
    .modern-button:nth-child(4):hover {
        background-color: #5a6a72; /* Darker Slate Gray */
    }
    
    .modern-button i {
        font-size: 24px;
    }
    
    
    @media (max-width: 768px) {
    .modern-button {
        width: 80%; 
        margin-bottom: 15px;
    }
}
 
    .modern-title {
        color: #482683;
        margin-bottom: 40px;
    }
    </style>
    
    <div class="modern-section">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between px-md-5 mb-5">
                <h1 class="fw-black h1 text-gym">Nos Services</h1>
            </div> 
            <div class="button-container d-flex  justify-content-center">
                <a class="modern-button" href="{{ route('masection') }}">
                    <i class="fas fa-bullseye"></i> 
                    Je trouve ma section
                </a>
                <a class="modern-button" href="{{ route('Simple_Post',13019) }}">
                    <i class="fas fa-question"></i> 
                    Questions Frequentes
                </a>
                <a class="modern-button" href="{{ route('index_mentions_legales') }}">
                    <i class="fas fa-balance-scale"></i> 
                    Mentions Légales

                </a>
                <a class="modern-button">
                    <i class="fas fa-star"></i> 
                    Animations Vacances
                </a>
            </div>
        </div>
    </div>
    