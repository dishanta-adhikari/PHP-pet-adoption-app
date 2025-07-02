<?php include 'includes/header.php'; ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Pet Adoption System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animate.css CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="css/index.css">
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        footer {
            margin-bottom: 0;
            padding-bottom: 1rem;
            /* keep some padding if you want */
        }
    </style>
</head>

<body>
    <div class="container hero-content">
        <h1 class="display-3 animate__animated animate__fadeInDown">🐾 Adopt a Pet 🐾</h1>
        <p class="lead animate__animated animate__fadeInUp animate__delay-1s">
            Bringing happiness to homes and hope to pets.
        </p>

        <div class="search-bar animate__animated animate__fadeInUp animate__delay-2s">
            <form class="d-flex justify-content-center" action="search_results" method="GET">
                <input class="form-control me-2 w-75" name="query" type="search" placeholder="Search by city, breed or NGO" aria-label="Search" required />
                <button class="btn btn-warning" type="submit">Search</button>
            </form>
        </div>

        <div class="animate__animated animate__zoomIn animate__delay-3s">
            <button class="btn btn-warning btn-custom" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
            <button class="btn btn-outline-light btn-custom" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="container section text-center">
        <h2 class="mb-4">💬 Success Stories</h2>
        <div class="row justify-content-center">
            <div class="col-md-4 testimonial">
                <p>"Adopted my first cat from here! The process was smooth and the volunteers were so helpful."</p>
                <strong>- Anjali, Mumbai</strong>
            </div>
            <div class="col-md-4 testimonial">
                <p>"We gave a home to Max, our beloved Labrador. Thank you for making it happen."</p>
                <strong>- Rohit, Delhi</strong>
            </div>
        </div>
    </div>

    <!-- Featured Pets Section -->
    <div class="marquee-container">
        <div class="marquee-content">
            <!-- Original set -->
            <div class="featured-pet">
                <img src="assets/images/dog1.jpg" alt="Bella" />
                <h5>Bella</h5>
                <p>2-year-old Beagle, very friendly and loves kids.</p>
            </div>
            <div class="featured-pet">
                <img src="assets/images/parrot.jpg" alt="Rocky" />
                <h5>Rocky</h5>
                <p>Energetic German Shepherd, trained and vaccinated.</p>
            </div>
            <div class="featured-pet">
                <img src="assets/images/dog1.jpg" alt="Luna" />
                <h5>Luna</h5>
                <p>Playful tabby cat, loves cuddles and naps.</p>
            </div>
            <!-- Duplicate set for seamless scrolling -->
            <div class="featured-pet">
                <img src="assets/images/parrot.jpg" alt="Bella" />
                <h5>Bella</h5>
                <p>2-year-old Beagle, very friendly and loves kids.</p>
            </div>
            <div class="featured-pet">
                <img src="assets/images/dog1.jpg" alt="Rocky" />
                <h5>Rocky</h5>
                <p>Energetic German Shepherd, trained and vaccinated.</p>
            </div>
            <div class="featured-pet">
                <img src="assets/images/parrot.jpg" alt="Luna" />
                <h5>Luna</h5>
                <p>Playful tabby cat, loves cuddles and naps.</p>
            </div>
        </div>
    </div>



    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Register As</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <a href="register?role=adopter" class="btn btn-outline-light btn-lg m-2 w-75">Adopter</a>
                    <a href="register?role=ngo" class="btn btn-outline-light btn-lg m-2 w-75">NGO</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login As</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <a href="login?role=adopter" class="btn btn-outline-light btn-lg m-2 w-75">Adopter</a>
                    <a href="login?role=ngo" class="btn btn-outline-light btn-lg m-2 w-75">NGO</a>
                    <a href="login?role=admin" class="btn btn-outline-light btn-lg m-2 w-75">Admin</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-warning text-dark pt-5 pb-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold">About Us</h5>
                    <p class="fw-semibold">Providing loving homes for pets and connecting pet lovers everywhere.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#home" class="text-dark text-decoration-none fw-semibold">Home</a></li>
                        <li><a href="#featured" class="text-dark text-decoration-none fw-semibold">Featured Pets</a></li>
                        <li><a href="#contact" class="text-dark text-decoration-none fw-semibold">Contact</a></li>
                        <li><a href="#register" class="text-dark text-decoration-none fw-semibold">Register</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold">Follow Us</h5>
                    <div>
                        <a href="#" class="text-dark fs-4 me-3" aria-label="Facebook"><i class="bi bi-facebook">Facebook</i></a>
                        <a href="#" class="text-dark fs-4 me-3" aria-label="Twitter"><i class="bi bi-twitter">Twitter</i></a>
                        <a href="#" class="text-dark fs-4" aria-label="Instagram"><i class="bi bi-instagram">Instagram</i></a>
                    </div>
                </div>
            </div>
            <div class="text-center border-top pt-3 mt-3 small">
                &copy; 2025 PetConnect. All rights reserved.
            </div>
        </div>
    </footer>


    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>