<!-- NOTE: LEFT MENU -->
<div class="leftMenu d-flex flex-column justify-content-between">
    <div>
        <h4 class="leftMenu-title">Show/Hide</h4>
        <ul class="leftMenu-list list-group">
            <?= navItem(
                "All notes", 
                "list-ul", 
                class: "toggleBtn showAllNotes", 
                data: ["target" => "#allNotes"]
            ) ?>

            <?= navItem(
                "My notes", 
                "person-fill", 
                class: "toggleBtn myNotes", 
                data: ["target" => "#allNotes"]
            ) ?>
        </ul>
    </div>

    <div>
        <h4 class="leftMenu-title">Actions</h4>
        <ul class="list-group">
            <?= navItem(
                "Add note", 
                "pencil-square", 
                color: "success", 
                class: "toggleBtn", 
                data: ["target" => ".addNote"]
            ) ?>

            <?= navItem(
                "Refresh", 
                "arrow-clockwise", 
                color: "primary", 
                class: "refresh text-primary",
                href: "index.php"
            ) ?>
        </ul>
    </div>

    <div>
        <h4 class="leftMenu-title text-danger">Danger Zone</h4>
        <ul class="leftMenu-list list-group">
            <?= navItem(
                "Delete all", 
                "trash", 
                color: "danger", 
                class: "delAll"
            ) ?>
        </ul>
    </div>
</div>

            <!-- <?= navItem("Shared by me", "people-fill", class: "navBtn sharedByMe") ?> -->
            <!-- <?= navItem("Shared with me", "people-fill", class: "navBtn sharedWithMe") ?> -->