<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('/vendor/horizon/img/favicon.png') }}">

    <title>Horizon{{ config('app.name') ? ' - ' . config('app.name') : '' }}</title>

    <!-- Style sheets-->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset(mix($cssFile, 'vendor/horizon')) }}" rel="stylesheet">
</head>
<body>
<div id="horizon" v-cloak>
    <alert :message="alert.message"
           :type="alert.type"
           :auto-close="alert.autoClose"
           :confirmation-proceed="alert.confirmationProceed"
           :confirmation-cancel="alert.confirmationCancel"
           v-if="alert.type"></alert>

    <div class="container mb-5">
        <div class="d-flex align-items-center py-4 header">
            <svg class="logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30">
                <path class="fill-primary" d="M5.26176342 26.4094389C2.04147988 23.6582233 0 19.5675182 0 15c0-4.1421356 1.67893219-7.89213562 4.39339828-10.60660172C7.10786438 1.67893219 10.8578644 0 15 0c8.2842712 0 15 6.71572875 15 15 0 8.2842712-6.7157288 15-15 15-3.716753 0-7.11777662-1.3517984-9.73823658-3.5905611zM4.03811305 15.9222506C5.70084247 14.4569342 6.87195416 12.5 10 12.5c5 0 5 5 10 5 3.1280454 0 4.2991572-1.9569336 5.961887-3.4222502C25.4934253 8.43417206 20.7645408 4 15 4 8.92486775 4 4 8.92486775 4 15c0 .3105915.01287248.6181765.03811305.9222506z"/>
            </svg>

            <h4 class="mb-0 ml-2">
                <strong>Laravel</strong> Horizon{{ config('app.name') ? ' - ' . config('app.name') : '' }}</h4>

            <button class="btn btn-outline-primary ml-auto" :class="{active: autoLoadsNewEntries}" v-on:click.prevent="autoLoadNewEntries" title="Auto Load Entries">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="icon fill-primary">
                    <path d="M10 3v2a5 5 0 0 0-3.54 8.54l-1.41 1.41A7 7 0 0 1 10 3zm4.95 2.05A7 7 0 0 1 10 17v-2a5 5 0 0 0 3.54-8.54l1.41-1.41zM10 20l-4-4 4-4v8zm0-12V0l4 4-4 4z"></path>
                </svg>
            </button>
        </div>

        <div class="row mt-4">
            <div class="col-2 sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <router-link active-class="active" to="/dashboard" class="nav-link d-flex align-items-center pt-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M0 3c0-1.1.9-2 2-2h16a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3zm2 2v12h16V5H2zm8 3l4 5H6l4-5z"></path>
                            </svg>
                            <span>Dashboard</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link active-class="active" to="/monitoring" class="nav-link d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33-1.42 1.42-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path>
                            </svg>
                            <span>Monitoring</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link active-class="active" to="/metrics" class="nav-link d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M1 10h3v10H1V10zM6 0h3v20H6V0zm5 8h3v12h-3V8zm5-4h3v16h-3V4z"></path>
                            </svg>
                            <span>Metrics</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link active-class="active" to="/batches" class="nav-link d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M1 1h18v2H1V1zm0 8h18v2H1V9zm0 8h18v2H1v-2zM1 5h18v2H1V5zm0 8h18v2H1v-2z"/>
                            </svg>
                            <span>Batches</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link active-class="active" to="/jobs/pending" class="nav-link d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM7 6h2v8H7V6zm4 0h2v8h-2V6z"/>
                            </svg>
                            <span>Pending Jobs</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link active-class="active" to="/jobs/completed" class="nav-link d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z"></path>
                            </svg>
                            <span>Completed Jobs</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link active-class="active" to="/failed" class="nav-link d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"></path>
                            </svg>
                            <span>Failed Jobs</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link active-class="active" to="/tasklogs" class="nav-link d-flex align-items-center">
                            <svg height="533pt" viewBox="-79 -16 533 533.33257" width="533pt" xmlns="http://www.w3.org/2000/svg"><path d="m315.808594 33.949219h-80.355469c-9.105469-20.632813-29.535156-33.949219-52.089844-33.949219-22.558593 0-42.988281 13.316406-52.09375 33.949219h-80.359375c-30.3125.035156-54.875 24.601562-54.910156 54.914062v356.222657c.035156 30.3125 24.597656 54.878906 54.910156 54.914062h264.898438c30.3125-.035156 54.878906-24.601562 54.914062-54.914062v-356.222657c-.035156-30.3125-24.601562-54.878906-54.914062-54.914062zm-175.496094 25c5.867188 0 10.945312-4.082031 12.207031-9.820313 3.070313-13.976562 16.042969-24.128906 30.84375-24.128906s27.769531 10.152344 30.839844 24.128906c1.257813 5.738282 6.335937 9.820313 12.210937 9.820313h24.785157v10.96875c-.019531 16.511719-13.398438 29.890625-29.910157 29.910156h-75.855468c-16.515625-.019531-29.894532-13.398437-29.914063-29.910156v-10.96875zm205.410156 386.136719c-.015625 16.515624-13.398437 29.898437-29.914062 29.914062h-264.898438c-16.511718-.015625-29.890625-13.398438-29.910156-29.914062v-356.222657c.019531-16.515625 13.398438-29.894531 29.910156-29.914062h39.609375v10.96875c.035157 30.3125 24.601563 54.875 54.914063 54.910156h75.855468c30.3125-.035156 54.875-24.597656 54.910157-54.910156v-10.96875h39.609375c16.515625.019531 29.894531 13.398437 29.914062 29.914062zm0 0"/><path d="m245.226562 230.957031-82.0625 80.125-41.671874-40.675781c-4.902344-4.796875-12.753907-4.738281-17.59375.121094-4.835938 4.863281-4.855469 12.714844-.039063 17.597656l.167969.164062 50.398437 49.210938c4.859375 4.742188 12.613281 4.742188 17.46875 0l90.792969-88.648438c4.910156-4.828124 4.988281-12.714843.183594-17.640624-4.8125-4.925782-12.695313-5.035157-17.644532-.246094zm0 0"/></svg>
                            <span>Task Logs</span>
                        </router-link>
                    </li>
                </ul>
            </div>

            <div class="col-10">
                @if (! $assetsAreCurrent)
                    <div class="alert alert-warning">
                        The published Horizon assets are not up-to-date with the installed version. To update, run:<br/><code>php artisan horizon:publish</code>
                    </div>
                @endif

                @if ($isDownForMaintenance)
                    <div class="alert alert-warning">
                        This application is in "maintenance mode". Queued jobs may not be processed unless your worker is using the "force" flag.
                    </div>
                @endif

                <router-view></router-view>
            </div>
        </div>
    </div>
</div>

<!-- Global Horizon Object -->
<script>
    window.Horizon = @json($horizonScriptVariables);
</script>

<script src="{{asset(mix('app.js', 'vendor/horizon'))}}"></script>
<script src="{{asset(mix('app.js', 'vendor/jdlx/task'))}}"></script>
</body>
</html>
