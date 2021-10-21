# saofileserver
A tiny http free file server

# Author
Julio A. García López (juliosao@gmail.com)


# Features
- List files and subdirectories in a base directory
- Download files
- Upload files
- Create folders
- Navigate into a base directory childs
- Delete files into server
- Manage users
- Play some video files (Depending on codec of video)
- Play .mp3 and .ogg files (if browser supports)
- LGPL License
- Icon theme:
    - Typicons - by Stephen Hutchings - https://www.iconfinder.com/iconsets/typicons-2
    - Circle-icons by Nick Roach - https://www.iconfinder.com/iconsets/circle-icons-1

## Install
- Copy 'fileserver' folder in your server
- Edit fileserver/mod/fso/cfg to set your desired 'basedir' directory
- Run extras/setupdb.sh
- Access the saofileserver in order to get a setup screen

## Troubleshooting

### I Get "Access denied for user 'root'@'localhost'" error message at setup

Maybe your root installationg is wrong. Try to execute these queries in mysql in order to repair it:
```
CREATE USER 'root'@'localhost' IDENTIFIED BY 'root';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
```
