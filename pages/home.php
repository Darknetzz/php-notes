<div class="page" id="home">

<div class="card">
    <h3 class="card-header">Notes - Home</h3>
    <div class="card-body">
        <h4 class="text-success">Welcome to Notes!</h4>
        
            You can add notes here. They have a title and a content.<br>
            You can also edit and delete them.<br>

        <hr>

        <h4>How to use</h4>
        <ul class="list-group">
            <li class="list-group-item"><?= icon("info-circle", 20, "lightblue") ?> Click on the <b>Notes</b> button in the navigation bar to view all notes.</li>
            <li class="list-group-item"><?= icon("info-circle", 20, "lightblue") ?> Click on the <b>Add note</b> button in the navigation bar to add a note.</li>
            <li class="list-group-item"><?= icon("info-circle", 20, "lightblue") ?> Click on the <b>Settings</b> button in the navigation bar to view settings.</li>
        </ul>

        <br>

        <h4>Features</h4>
        <ul class="list-group">
            <li class="list-group-item"><?= icon("check-circle", 20, "green") ?> Markdown support</li>
            <li class="list-group-item"><?= icon("check-circle", 20, "green") ?> Dark mode</li>
            <li class="list-group-item"><?= icon("check-circle", 20, "green") ?> Customizable</li>
            <li class="list-group-item"><?= icon("check-circle", 20, "green") ?> Responsive</li>
            <li class="list-group-item"><?= icon("check-circle", 20, "green") ?> Fast</li>
            <li class="list-group-item"><?= icon("check-circle", 20, "green") ?> Easy to use</li>
            <li class="list-group-item"><?= icon("check-circle", 20, "green") ?> Open source</li>
        </ul>

        <br>

        <h4>Requirements</h4>
        <ul class="list-group">
            <li class="list-group-item"><?= icon("exclamation-circle", 20, "orange") ?> PHP 8.3+</li>
            <li class="list-group-item"><?= icon("exclamation-circle", 20, "orange") ?> Web server (Apache, Nginx, etc.)</li>
            <li class="list-group-item"><?= icon("exclamation-circle", 20, "orange") ?> Web browser (Firefox, Chrome, etc.)</li>
        </ul>

        <br>

        <h4>Installation</h4>
        <ol class="list-group">
            <li class="list-group-item">
                Download or clone from
                <br>
                <a href="<?= $project_url ?>" target="_blank" 
                    class="btn btn-dark text-light border-secondary" style="font-size: large; text-decoration: none;">
                    <?= icon("github", 20) ?> GitHub
                </a>
            </li>
            <li class="list-group-item">Extract the archive to your web server</li>
        </ol>

        <hr>

        Made with <?= icon("heart-fill", 15, "red") ?> by <a href="<?= $github_url ?>">Darknetzz</a>
        
    </div>
</div>