<md-toolbar class="md-whiteframe-glow-z1 site-content-toolbar _md">

    <div class="md-toolbar-tools docs-toolbar-tools" tabindex="-1">
        <button class="md-icon-button md-button md-ink-ripple hide-gt-sm" type="button" ng-transclude=""
                ng-click="openMenu()" hide-gt-sm="" aria-label="Toggle Menu">
            <md-icon md-svg-src="img/icons/ic_menu_24px.svg" class="ng-scope" aria-hidden="true">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                     y="0px" width="100%" height="100%" viewBox="0 0 24 24" enable-background="new 0 0 24 24"
                     xml:space="preserve" fit="" preserveAspectRatio="xMidYMid meet" focusable="false">
                    <g id="Header">
                        <g>
                            <rect x="-618" y="-2232" fill="none" width="1400" height="3600"></rect>
                        </g>
                    </g>
                    <g id="Label">
                    </g>
                    <g id="Icon">
                        <g>
                            <rect fill="none" width="24" height="24"></rect>
                            <path d="M3,18h18v-2H3V18z M3,13h18v-2H3V13z M3,6v2h18V6H3z"></path>
                        </g>
                    </g>
                    <g id="Grid" display="none">
                        <g display="inline">
                        </g>
                    </g>
                </svg>
            </md-icon>
        </button>
        <div layout="row" flex="" class="fill-height layout-row flex">
            <h2 class="md-toolbar-item md-breadcrumb md-headline">
                <!-- ngIf: menu.currentPage.name !== menu.currentSection.name --><span
                        ng-if="menu.currentPage.name !== menu.currentSection.name" class="ng-scope">
              <span hide-sm="" hide-md="" class="hide-md hide-sm ng-binding">Demos</span>
              <span class="docs-menu-separator-icon hide-md hide-sm" hide-sm="" hide-md=""
                    style="transform: translate3d(0, 1px, 0)">
                <span class="_md-visually-hidden">-</span>
                <md-icon aria-hidden="true" md-svg-src="img/icons/ic_chevron_right_24px.svg" style="margin-top: -2px"><svg
                            version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px" y="0px" width="100%" height="100%" viewBox="0 0 24 24"
                            enable-background="new 0 0 24 24" xml:space="preserve" fit=""
                            preserveAspectRatio="xMidYMid meet" focusable="false">
            <g id="Header">
                <g>
                    <rect x="-618" y="-1336" fill="none" width="1400" height="3600"></rect>
                </g>
            </g>
            <g id="Label">
            </g>
            <g id="Icon">
                <g>
                    <polygon points="10,6 8.6,7.4 13.2,12 8.6,16.6 10,18 16,12"></polygon>
                    <rect fill="none" width="24" height="24"></rect>
                </g>
            </g>
            <g id="Grid" display="none">
                <g display="inline">
                </g>
            </g>
            </svg>
                </md-icon>
              </span>
            </span><!-- end ngIf: menu.currentPage.name !== menu.currentSection.name -->
                <span class="md-breadcrumb-page ng-binding">Toolbar</span>
            </h2>

            <span flex="" class="flex"></span> <!-- use up the empty space -->

            <div class="md-toolbar-item docs-tools layout-row" layout="row">
                <!-- ngIf: !currentComponent.docs.length && !currentComponent.isService -->
                <!-- ngIf: !currentComponent.docs.length && !currentComponent.isService -->
                <!-- ngIf: !currentComponent.docs.length && !currentComponent.isService -->

                <!-- ngIf: currentComponent.docs.length -->
                <md-select ng-if="currentComponent.docs.length" ng-class="{ hide: path().indexOf('demo') == -1 }"
                           ng-model="relatedPage" placeholder="API Reference"
                           class="md-body-1 ng-pristine ng-untouched ng-valid ng-scope ng-empty" tabindex="0"
                           aria-disabled="false" role="listbox" aria-expanded="false" aria-multiselectable="false"
                           id="select_1" aria-owns="select_container_2" aria-invalid="false" aria-label="API Reference">
                    <md-select-value class="_md-select-value _md-select-placeholder" id="select_value_label_0"><span>API Reference</span><span
                                class="_md-select-icon" aria-hidden="true"></span></md-select-value>
                    <div class="_md-select-menu-container" aria-hidden="true" id="select_container_2">
                        <md-select-menu class="_md">
                            <md-content class="_md">
                                <!-- ngIf: (currentComponent.docs | filter: { type: 'directive' }).length -->
                                <md-optgroup label="Directives"
                                             ng-if="(currentComponent.docs | filter: { type: 'directive' }).length"
                                             class="ng-scope"><label class="_md-container-ignore">Directives</label>
                                    <!-- ngRepeat: doc in currentComponent.docs | filter: { type: 'directive' } -->
                                    <md-option ng-repeat="doc in currentComponent.docs | filter: { type: 'directive' }"
                                               ng-value="doc.url" ng-click="redirectToUrl(doc.url)"
                                               aria-label="md-toolbar" tabindex="0" class="ng-scope md-ink-ripple"
                                               role="option" aria-selected="false" id="select_option_3"
                                               aria-checked="true" value="api/directive/mdToolbar">
                                        <div class="_md-text ng-binding">
                                            &lt;md-toolbar&gt;
                                        </div>
                                    </md-option>
                                    <!-- end ngRepeat: doc in currentComponent.docs | filter: { type: 'directive' } -->
                                </md-optgroup>
                                <!-- end ngIf: (currentComponent.docs | filter: { type: 'directive' }).length -->
                                <!-- ngIf: (currentComponent.docs | filter: { type: 'service' }).length -->
                            </md-content>
                        </md-select-menu>
                    </div>
                </md-select><!-- end ngIf: currentComponent.docs.length -->

                <a class="md-icon-button md-button md-ink-ripple hide" ng-transclude="" aria-label="View Demo"
                   ng-class="{hide: !currentDoc || !currentDoc.hasDemo}" ng-href="demo/toolbar" href="demo/toolbar">
                    <md-icon md-svg-src="img/icons/ic_play_circle_fill_24px.svg" style="fill:green" class="ng-scope"
                             aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24" fit=""
                             preserveAspectRatio="xMidYMid meet" focusable="false">
                            <path d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"></path>
                        </svg>
                    </md-icon>

                </a>

                <a class="md-icon-button md-button md-ink-ripple hide-sm hide-md hide" ng-transclude=""
                   aria-label="View Source on Github" ng-class="{hide: !currentDoc}" ng-href="" hide-sm="" hide-md="">
                    <md-icon md-svg-src="img/icons/github.svg" style="color: rgba(255,255,255,0.97);" class="ng-scope"
                             aria-hidden="true">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="8 11 33 28" fit="" height="100%" width="100%" preserveAspectRatio="xMidYMid meet"
                             focusable="false">
                            <path fill="#333"
                                  d="M11.828,18.179c0,0-3.344,3.499-2.763,9.192 c0.581,5.694,4.186,10.392,16.208,10.392c12.021,0,15.045-6.275,15.116-11.436c0.071-5.159-2.253-7.46-3.344-8.243 c0,0,0.007-3.704-0.343-5.661c0,0-1.85-0.219-5.845,2.07c0,0-5.454-0.533-12.722,0.065c0,0-3.053-2.04-6.129-2.574 C12.006,11.984,11.496,15.196,11.828,18.179z"></path>
                            <path fill="#E2B89F"
                                  d="M17.211,23.815h14.916c0,0,4.227-0.475,4.227,6.44 c0.034,6.086-11.139,5.693-11.139,5.693s-12.236,0.486-12.243-6.269C12.956,23.579,17.211,23.815,17.211,23.815z"></path>
                            <g>
                                <g>
                                    <path fill="#9C584F"
                                          d="M30.767,26.591c0.959,0,1.737,1.25,1.737,2.787 c0,1.54-0.778,2.788-1.737,2.788c-0.958,0-1.736-1.248-1.736-2.788C29.03,27.841,29.809,26.591,30.767,26.591z"></path>
                                    <path fill="#FFFFFF"
                                          d="M30.767,32.666c-1.254,0-2.236-1.444-2.236-3.288c0-1.843,0.982-3.287,2.236-3.287 c1.255,0,2.237,1.444,2.237,3.287C33.004,31.222,32.021,32.666,30.767,32.666z M30.767,27.091c-0.585,0-1.236,0.939-1.236,2.287 c0,1.349,0.651,2.288,1.236,2.288s1.237-0.939,1.237-2.288C32.004,28.03,31.352,27.091,30.767,27.091z"></path>
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path fill="#9C584F"
                                          d="M18.767,26.591c0.959,0,1.737,1.25,1.737,2.787 c0,1.54-0.778,2.788-1.737,2.788c-0.958,0-1.736-1.248-1.736-2.788C17.03,27.841,17.809,26.591,18.767,26.591z"></path>
                                    <path fill="#FFFFFF"
                                          d="M18.767,32.666c-1.254,0-2.236-1.444-2.236-3.288c0-1.843,0.982-3.287,2.236-3.287 c1.254,0,2.237,1.444,2.237,3.287C21.004,31.222,20.021,32.666,18.767,32.666z M18.767,27.091c-0.585,0-1.236,0.939-1.236,2.287 c0,1.349,0.651,2.288,1.236,2.288c0.585,0,1.237-0.939,1.237-2.288C20.004,28.03,19.352,27.091,18.767,27.091z"></path>
                                </g>
                            </g>
                            <path fill="#9C584F"
                                  d="M24.076,32.705c0,0,0.499-1.418,1.109-0.089 c0,0-0.457,0.297-0.285,0.996l1.428,0.546h-3.23l1.28-0.575C24.378,33.583,24.562,32.527,24.076,32.705z"></path>
                        </svg>
                    </md-icon>

                </a>
            </div>
        </div>
    </div>

</md-toolbar>