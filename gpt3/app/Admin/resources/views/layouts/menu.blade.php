<li class="{{ Request::is('/') ? 'active' : '' }}">
    <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i><span>Dashboard</span></a>
</li>

<li class="header">MAIN NAVIGATION</li>

<li class="{{ Request::is('users*') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}"><i class="fa fa-users"></i><span>Users</span></a>
</li>

<li class="active treeview menu-open" style="height: auto;">
  <a href="#">
    <i class="fa fa-cubes"></i> <span>Codes Management</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>

  <ul class="treeview-menu" style="">
    <li class="{{ Request::is('codes*') ? 'active' : '' }}">
        <a href="{!! route('codes.index') !!}"><i class="fa fa-edit"></i><span>Codes</span></a>
    </li>
  </ul>
  
  <ul class="treeview-menu" style="">
    <li class="{{ Request::is('code-data*') ? 'active' : '' }}">
        <a href="{!! route('code-data.index') !!}"><i class="fa fa-edit"></i><span>Code Verification</span></a>
    </li>
  </ul>
  
</li>
