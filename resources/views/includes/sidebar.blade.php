<!-- Sidebar  -->
<nav id="sidebar">
    <ul class="list-unstyled components">
        <li>
            <a href="{{ url('/colleges') }}"><i class="fa fa-university"></i> Colleges</a>
        </li>
        <li>
            <a href="#userSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-users"></i> Users</a>
            <ul class="collapse list-unstyled" id="userSubmenu">
                <li>
                    <a href="{{ url('/faculties') }}">Faculties</a>
                </li>
                <li>
                    <a href="#">Students</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#"><i class="fa fa-question"></i> About</a>
        </li>
        <li>
            <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-user"></i> Pages</a>
            <ul class="collapse list-unstyled" id="pageSubmenu">
                <li>
                    <a href="#">Page 1</a>
                </li>
                <li>
                    <a href="#">Page 2</a>
                </li>
                <li>
                    <a href="#">Page 3</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">Portfolio</a>
        </li>
        <li>
            <a href="#">Contact</a>
        </li>
    </ul>
</nav>