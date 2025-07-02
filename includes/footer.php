<style>
    /* Sticky footer fixed to bottom */

    .sticky-footer {
        background-color: #ffb300;
        /* Bright amber */
        color: #212529;
        padding: 0.75rem 0;
        position: fixed;
        /* changed from sticky to fixed */
        bottom: 0;
        left: 0;
        width: 100%;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        font-weight: 600;
        font-size: 0.9rem;
        z-index: 1030;
    }

    /* Make sure body and container fill viewport height so content above footer pushes footer down */

    body {
        min-height: 100vh;
        margin: 0;
        /* reset default margin */
        padding-bottom: 50px;
        /* height of footer approx */
        display: flex;
        flex-direction: column;
    }

    .container {
        flex: 1 0 auto;
    }
</style>
<footer class="sticky-footer">
    <div class="container text-center">
        <small>&copy; <?= date('Y') ?> Pet Adoption Portal. All rights reserved.</small>
    </div>
</footer>