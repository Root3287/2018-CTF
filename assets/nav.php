<?php
$user = new Root3287\classes\User();
$navbar = json_decode(Root3287\classes\Setting::get('navbar_top'), true)['links'];
?>
<nav class="navbar navbar-expand-md bg-dark navbar-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="/">Navbar</a>

  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <?php
      foreach ($navbar as $nav => $navItem) {
        if($navItem["type"] == "link"){
          //$navItem["name"];
          //$navItem["content"]
          //
          // $navItem["haveLogIn"] == true excludes all users who haven't logged in.
          if(isset($navItem["haveLogIn"]) && $navItem["haveLogIn"] && !$user->isLoggedIn()){
            continue;
          }

          // $navItem["excludeGroups"] black list groups to access this.
          if(isset($navItem["excludeGroups"]) && count($navItem["excludeGroups"]) > 0){
            $flag = false;
            foreach ($navItem["excludeGroups"] as $g) {
              if($user->isLoggedIn() && $user->data()->group == $g){
                $flag = true;
                break;
              }
            }
            if($flag){
              continue;
            }
          } 
      ?>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $navItem["content"];?>"><?php echo $navItem["name"]; ?></a>
        </li>
      <?php
        }else if($navItem["type"] == "multi-link"){
      ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $navItem["name"]; ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <?php
            foreach ($navItem["content"] as $content => $link) {
              //$link["name"]
              //$link["content"]
              if(isset($link["haveLogIn"]) && $link["haveLogIn"] && !$user->isLoggedIn()){
                continue;
              }

              if(isset($link["excludeGroups"]) && count($link["excludeGroups"]) > 0){
                $flag = false;
                foreach ($link["excludeGroups"] as $g) {
                  if($user->isLoggedIn() && $user->data()->group == $g){
                    $flag = true;
                    break;
                  }
                }
                if($flag){
                  continue;
                }
              } 
            ?>
                <a class="dropdown-item" href="<?php echo $link["content"];?>"><?php echo $link["name"]; ?></a>
            <?php
            }
            ?>
          </div>
        </li>
        <?php
        }
      }
      ?>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php if($user->isLoggedIn()){echo $user->data()->username;}else{echo "User";}?></a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <?php if($user->isLoggedIn()){ ?>
            <a class="dropdown-item" href="/user/">UserCP</a>
            <?php if($user->hasPermission('Mod')): ?>
              <a class="dropdown-item" href="/login">ModCP</a>
            <?php endif; ?>
            <?php if($user->hasPermission('Admin')): ?>
              <a class="dropdown-item" href="/admin">AdminCP</a>
            <?php endif; ?>
              <div class="dropdown-divider"></div>
              <a href="/logout" class="dropdown-item">Logout</a>
          <?php }else{ ?>
            <a class="dropdown-item" href="/login">Login</a>
            <a class="dropdown-item" href="/register">Register</a>
          <?php } ?>
        </div>
      </li>
    </ul>
  </div>
</nav>