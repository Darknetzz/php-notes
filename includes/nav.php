<!-- NOTE: LEFT MENU -->
<div class="leftMenu d-flex flex-column justify-content-top">
    <?php
    if (!isset($_SESSION['id'])) {
        echo "<div>
                <h4 class='leftMenu-title'>Account</h4>
                <ul class='leftMenu-list list-group'>
                    ".navItem(
                        label: "Login", 
                        icon: "box-arrow-in-right", 
                        color: "success", 
                        class: "navBtn login",
                        attrs: ["data-target" => "#login"],
                    )."
                </ul>
            </div>";
    } else {
    ?>
    <div class="mb-3">
        <h4 class="leftMenu-title">Navigation</h4>
        <ul class="list-group">
            <?php
            echo
            navItem(
                label: "Home", 
                icon: "house-fill", 
                color: "light", 
                class: "navBtn home", 
                attrs: ["data-target" => "#home"]
            ).
            navItem(
                label: "Notes", 
                icon: "book-fill", 
                color: "light", 
                class: "navBtn home",
                attrs: ["data-target" => "#notes"]
            )
            .
            navItem(
                label: "Settings", 
                icon: "gear-fill", 
                color: "light", 
                class: "navBtn settings",
                attrs: ["data-target" => "#settings"],
            )
            .
            navItem(
                label: "Refresh", 
                icon: "arrow-clockwise", 
                color: "light",
                class: "refresh",
                href: "index.php"
            )
            ?>
        </ul>
    </div>

    
    <div class="mb-3">
        <h4 class="leftMenu-title">Show/Hide</h4>
        <ul class="leftMenu-list list-group">
            <?php
            echo
                navItem(
                label: "All notes", 
                icon: "list-ul", 
                class: "navBtn", 
                attrs: ["data-target" => "#notes"]
                ).
                navItem(
                label: "My notes", 
                icon: "person-fill", 
                class: "navBtn myNotes", 
                attrs: ["data-target" => "#myNotes"]
                )
                .
                navItem(
                    label: "Add note", 
                    icon: "plus-circle", 
                    color: "light", 
                    class: "navBtn", 
                    attrs: ["data-target" => "#addNoteCard"]
                );
            ?>
        </ul>
    </div>


    <div class="mb-3">
        <h4 class="leftMenu-title">Account</h4>
        <ul class="leftMenu-list list-group">
            <?php
            echo
            navItem(
                label: "Profile", 
                icon: "person-fill", 
                color: "info", 
                class: "profile"
            )
            .
            navItem(
                label: "Sign out", 
                icon: "box-arrow-right", 
                color: "danger", 
                class: "signOut"
            )
            ?>
        </ul>
    </div>

    <div class="mb-3">
        <h4 class="leftMenu-title text-danger">Danger Zone</h4>
        <ul class="leftMenu-list list-group">
            <?php
            echo
            navItem(
                label: "Backup notes", 
                icon: "cloud-arrow-down-fill", 
                color: "light", 
                class: "backup"
            )
            .
            navItem(
                label: "Delete all notes", 
                icon: "trash", 
                color: "danger", 
                class: "delAll"
            );

            ?>
        </ul>
    </div>

    <?php } ?>
</div>

<!-- <?= navItem(label: "Shared by me", icon: "people-fill", class: "navBtn sharedByMe") ?> -->
<!-- <?= navItem(label: "Shared with me", icon: "people-fill", class: "navBtn sharedWithMe") ?> -->
