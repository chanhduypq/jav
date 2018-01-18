<nav class="navbar navbar-default" style="margin-bottom: 5px;">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/index.php">Jav Tool</a>
        </div>
        <ul class="nav navbar-nav">
            <li<?php if (strpos($_SERVER['SCRIPT_NAME'], 'index') !== FALSE&&strpos($_SERVER['SCRIPT_NAME'], 'torrent') === FALSE&&strpos($_SERVER['SCRIPT_NAME'], 'search_engine') === FALSE&&strpos($_SERVER['SCRIPT_NAME'], 'sites') === FALSE) echo ' class="active"'; ?>><a href="/index.php">Database Search</a></li>
            <li<?php if (strpos($_SERVER['SCRIPT_NAME'], 'track') !== FALSE) echo ' class="active"'; ?>><a href="/track.php">Jav Track</a></li>
            <li<?php if (strpos($_SERVER['SCRIPT_NAME'], 'statistic') !== FALSE) echo ' class="active"'; ?>><a href="/statistic.php">Statistic</a></li>
            <li<?php if (strpos($_SERVER['SCRIPT_NAME'], 'rules') !== FALSE) echo ' class="active"'; ?>><a href="/rules.php">Rules</a></li>
            <li<?php if (strpos($_SERVER['SCRIPT_NAME'], 'ultrasound') !== FALSE) echo ' class="active"'; ?>><a href="/ultrasound.php">Copyright !</a></li>
            <li<?php if (strpos($_SERVER['SCRIPT_NAME'], 'sites') !== FALSE) echo ' class="active"'; ?>><a href="/sites">Instant Search</a></li>
            <li<?php if (strpos($_SERVER['SCRIPT_NAME'], 'torrent') !== FALSE) echo ' class="active"'; ?>><a href="/torrent/">Torrent Search</a></li>
            <li<?php if (strpos($_SERVER['SCRIPT_NAME'], 'search_engine') !== FALSE) echo ' class="active"'; ?>><a href="/search_engine/">Engine Search</a></li>
        </ul>
    </div>
</nav>