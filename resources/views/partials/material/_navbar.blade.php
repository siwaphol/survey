<md-sidenav
        class="md-sidenav-left md-whiteframe-4dp"
        md-component-id="left"
        md-is-locked-open="$mdMedia('gt-md')"
        md-whiteframe="4">
    <md-toolbar class="md-theme-indigo">
        <h1 class="md-toolbar-tools">Sidenav Left</h1>
    </md-toolbar>
    <md-content layout-padding ng-controller="LeftCtrl">
        <md-button ng-click="close()" class="md-primary">
            Close Sidenav Left
        </md-button>
    </md-content>
</md-sidenav>