<?php 
include 'include/db.php'; 
include 'include/header.php'; 

// Traitement du message (optionnel : tu peux l'enregistrer en base de données)
$message_envoye = false;
if (isset($_POST['envoyer'])) {
    // Ici, on pourrait faire un INSERT dans une table 'contacts'
    $message_envoye = true;
}
?>

<div class="container-fluid bg-primary py-5 mb-5 page-header">
    <div class="container py-5 text-center">
        <h1 class="display-3 text-white animated slideInDown">Contactez-nous</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a class="text-white" href="index.php">Accueil</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Contact</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title bg-white text-center text-primary px-3">Une question ?</h6>
            <h1 class="mb-5">Contact pour toute suggestion</h1>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <h5>Nos Coordonnées</h5>
                <p class="mb-4">Besoin d'aide pour trouver une épreuve spécifique ou envie de contribuer au projet Nvadigital ?</p>
                <div class="d-flex align-items-center mb-3">
                    <div class="d-flex align-items-center justify-content-center flex-shrink-0 bg-primary" style="width: 50px; height: 50px;">
                        <i class="fa fa-map-marker-alt text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="text-primary">Localisation</h5>
                        <p class="mb-0">Cotonou, Bénin</p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <div class="d-flex align-items-center justify-content-center flex-shrink-0 bg-primary" style="width: 50px; height: 50px;">
                        <i class="fa fa-phone-alt text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="text-primary">Téléphone</h5>
                        <p class="mb-0">+229 42 43 51 05</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center flex-shrink-0 bg-primary" style="width: 50px; height: 50px;">
                        <i class="fa fa-envelope-open text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="text-primary">Email</h5>
                        <p class="mb-0"></p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <iframe class="position-relative rounded w-100 h-100"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126899.41842841402!2d2.348421048404286!3d6.370428511477759!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x102355099309265f%3A0xc6657c7064d73b0a!2sCotonou!5e0!3m2!1sfr!2sbj!4v1711111111111!5m2!1sfr!2sbj"
                    frameborder="0" style="min-height: 300px; border:0;" allowfullscreen="" aria-hidden="false"
                    tabindex="0"></iframe>
            </div>

            <div class="col-lg-4 col-md-12 wow fadeInUp" data-wow-delay="0.5s">
                <?php if ($message_envoye): ?>
                    <div class="alert alert-success">Merci ! Votre message a été bien transmis.</div>
                <?php endif; ?>
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" placeholder="Votre Nom" required>
                                <label for="name">Votre Nom</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" placeholder="Votre Email" required>
                                <label for="email">Votre Email</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="subject" placeholder="Sujet" required>
                                <label for="subject">Sujet</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Votre message" id="message" style="height: 150px" required></textarea>
                                <label for="message">Message</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 py-3" name="envoyer" type="submit">Envoyer le message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>