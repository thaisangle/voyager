<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <img src="cms-admin/images/user.png" width="48" height="48" alt="User" />
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{Auth()->user()->name}}</div>
                <div class="email">{{Auth()->user()->email}}</div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li role="separator" class="divider"></li>
                        
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();"><i class="material-icons">input</i>Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- #User Info -->
        
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="header">MAIN NAVIGATION</li>
                <li id="home">
                    <a href="cms">
                        <span><i class="fas fa-home"></i> Home</span>
                    </a>
                </li>
                <li id="user">
                    <a href="{{route('user.index')}}">
                        <span><i class="fas fa-users"></i> User</span>
                    </a>
                </li>
                <li id="product">
                    <a href="{{route('product.index')}}">
                        <span><i class="fas fa-female"></i> Dress</span>
                    </a>
                </li>
                <li id="config">
                    <a href="{{route('config.index')}}">
                        <span><i class="fas fa-cogs"></i> Config</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- #Menu -->

        <!-- Footer -->
        <div class="legal">
            <div class="copyright">
                &copy; 2019 - 2020 <a href="javascript:void(0);">AdminCMS - Dreesu</a>.
            </div>
            <div class="version">
                <b>Version: </b>   1
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->
    
</section>
