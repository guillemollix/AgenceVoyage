Projet PHP/Symphony de Thomas HERVIER et Guillem CANTO
 
 Certaines données de tests sont chargées par les fixtures mais
 - pas d'images dans les annonces (on peut en ajouter manuellement)
 - pas de commentaires (on peut en ajouter manuellement quand même)
 
 la liste des routes :
 - toutes les routes en /owner/ ou en /client/ sont réservées au rôle ADMIN à l'exception de /owner/new et /client/new
 - region_index et region_show sont accessibles à tous mais le reste est réservé aux admins
 - toutes les routes de /room sont accessibles aux utilisateurs normaux avec les conditions d'avoir un compte pour créer et d'être le propriétaire pour modifier et supprimer
 - à la création d'un compte utilisateur, ce dernier n'est ni client ni propriétaire, il peut le devenir en renseignant des champs supplémantaires à partir des liens au même endroit que la connection
 - les réservations peuvent êtres crées par chaque utilisateurs connecté et client (et peuvent êtres vues et modifiées par tt le monde (pas fini))
 
 il y a deux comptes par défaut :
 - chris@localhost mdp chris USER et OWNER
 - anna@localhost mdp anna ADMIN et OWNER
 
 les deux sont propriétaires d'une chambre
 
 -------------------------- ---------- -------- ------ -----------------------------------
  Name                       Method     Scheme   Host   Path                              
 -------------------------- ---------- -------- ------ -----------------------------------

  client_index               GET        ANY      ANY    /client/
  client_new                 GET|POST   ANY      ANY    /client/new
  client_show                GET        ANY      ANY    /client/{id}
  client_edit                GET|POST   ANY      ANY    /client/{id}/edit
  client_delete              DELETE     ANY      ANY    /client/{id}
  home                       ANY        ANY      ANY    /
  owner_index                GET        ANY      ANY    /owner/
  owner_new                  GET|POST   ANY      ANY    /owner/new
  owner_show                 GET        ANY      ANY    /owner/{id}
  owner_edit                 GET|POST   ANY      ANY    /owner/{id}/edit
  owner_delete               DELETE     ANY      ANY    /owner/{id}
  region_index               GET        ANY      ANY    /region/
  region_new                 GET|POST   ANY      ANY    /region/new
  region_show                GET        ANY      ANY    /region/{id}
  region_edit                GET|POST   ANY      ANY    /region/{id}/edit
  region_delete              DELETE     ANY      ANY    /region/{id}
  app_register               ANY        ANY      ANY    /register
  reservation_index          GET        ANY      ANY    /reservation/
  reservation_new            GET|POST   ANY      ANY    /reservation/new/{room_id}
  reservation_show           GET        ANY      ANY    /reservation/{id}
  reservation_edit           GET|POST   ANY      ANY    /reservation/{id}/edit
  reservation_delete         DELETE     ANY      ANY    /reservation/{id}
  room_index                 GET        ANY      ANY    /room/
  room_likes                 GET        ANY      ANY    /room/likes
  room_by_region             ANY        ANY      ANY    /room/region/{regionString}
  room_new                   GET|POST   ANY      ANY    /room/new
  room_show                  GET|POST   ANY      ANY    /room/{id}
  room_edit                  GET|POST   ANY      ANY    /room/{id}/edit
  room_delete                DELETE     ANY      ANY    /room/{id}
  room_like                  GET        ANY      ANY    /room/{id}/like
  app_login                  ANY        ANY      ANY    /login
  app_logout                 ANY        ANY      ANY    /logout
