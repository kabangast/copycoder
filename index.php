<?php include('templates/header.php'); ?>

<div class="container-fluid bg-dark text-white min-vh-100">
    <div class="container py-2">
        <!-- Hero Section -->
        <?php include('templates/hero_section.php'); ?>
        
        <!-- Main Content -->
        <div class="row mt-3">
            <div class="col-md-16">
                <?php include('templates/file_upload.php'); ?>
            </div>
        </div>
        
        <!-- Recent Prompts -->
        <?php include('templates/recent_prompts.php'); ?>
    </div>
</div>

<?php include('templates/footer.php'); ?>
