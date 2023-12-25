<div class="page" id="home">

<div class="card">
    <h3 class="card-header">Notes - Home</h3>
    <div class="card-body">
        <h4 class="text-success">Welcome to Notes!</h4>
            You can add notes here. They have a title and a content.<br>
            You can also edit and delete them.<br>

        <br>

        <h4>How to use</h4>
        <ul>
            <li>Click on the <b>Notes</b> button in the navigation bar to view all notes.</li>
            <li>Click on the <b>Add note</b> button in the navigation bar to add a note.</li>
            <li>Click on the <b>Settings</b> button in the navigation bar to view settings.</li>
        </ul>

        <br>

        <h4>Features</h4>
        <ul>
            <li><input type="checkbox" checked disabled> Markdown support</li>
            <li><input type="checkbox" checked disabled> Dark mode</li>
            <li><input type="checkbox" checked disabled> Customizable</li>
            <li><input type="checkbox" checked disabled> Responsive</li>
            <li><input type="checkbox" checked disabled> Fast</li>
            <li><input type="checkbox" checked disabled> Easy to use</li>
            <li><input type="checkbox" checked disabled> Open source</li>
        </ul>

        <br>

        <h4>Requirements</h4>
        <ul>
            <li>PHP 8.3+</li>
            <li>Web server (Apache, Nginx, etc.)</li>
            <li>Web browser (Firefox, Chrome, etc.)</li>
        </ul>

        <br>

        <h4>Installation</h4>
        <ol>
            <li>
                Download the latest release from
                <a href="<?= $project_url ?>" target="_blank" 
                class="btn btn-dark text-light border-secondary" style="font-size: large; text-decoration: none;">
                <?= icon("github", 20) ?> GitHub
                </a>
            </li>
            <li>Extract the archive to your web server</li>
        </ol>

        <hr>

        Made with <?= icon("heart-fill", 15, "red") ?> by <a href="<?= $github_url ?>">Darknetzz</a>
        
    </div>
</div>