# virtualfolder

[![PHPUnit](https://github.com/icewind1991/virtualfolder/actions/workflows/phpunit.yml/badge.svg)](https://github.com/icewind1991/virtualfolder/actions)

Example app for creating virtual folders

## Using the app "as is"

The app in it's base form allows the admin to setup virtual folders using occ commands.

Once a folder is configured it will show up at the configured mount point for the target user as a folder
containing the provided files. The virtual folder itself will be read only while the configured files contained
inside will have the full permissions from the source user.

#### Create a new virtual folder

```bash
occ virtualfolder:create <source user> <target user> <mount point> [<file ids>...]
```

#### List created virtual folders

```bash
occ virtualfolder:list
```

#### Delete a virtual folder

```bash
occ virtualfolder:delete <folder id>
```

## Using the app as a base for your own app

The app is setup to be easily adaptable into apps that create virtual folders for specific use cases.

### Automatically setting up folders

Custom logic for configuring virtual folders can be setup in `lib/Folder/FolderConfigManager.php`
by adding your own logic to the `getFoldersForUser` method.
All other methods in that class are only used by unit tests and the management command and can thus be removed
if you remove the management commands.

### Limiting the permissions of files inside the virtual folder

Permissions of files in the virtual folder can be restricted by changing `getStorageForSourceFile`
in `lib/Mount/VirtualFolderMountProvider` to wrap the created `Jail` storage wrapper into a `PermissionsMask` wrapper
to apply a mask to the permissions for the files in the virtual folder.


## Code overview

- `AppInfo`
  - `Application`: setup the app by registering the mount provided
- `Command/*`: commands to allow the admin to configure virtual mounts
- `Folder`
  - `FolderConfig`: data class to store information for a configured folder
  - `FolderConfigManager`: manage configured virtual folders
  - `SourceFile`: holds the data of each source file in the virtual folder and allows access to the source storage
  - `VirtualFolder`: data class for virtual folders and source file information
  - `VirtualFolderFactory`: get folder and source file info from folder configuration
- `Migration`: database migration script for storing admin configured virtual folders
- `Mount`
  - `VirtualFolderMount`: `MountPoint` subclass to allow setting a custom folder icon
  - `VirtualFolderMountProvider`: mount provider that setups up all mounts required for the virtual folder  
	this includes one mount for the root of the virtual folder and one mount for every file inside it
- `Storage`
  - `EmptyStorage`: an empty readonly storage for use as the virtual folder root
  - `LazyWrapper`: a storage wrapper that allows delaying setup of the source storage until the storage is used 
  - `LazeCacheWrapper`: same as `LazyWrapper` but then for the source cache
