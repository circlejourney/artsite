# Loose ends
- Email verification
 - Add this to User class: `class User extends Authenticatable implements MustVerifyEmail`
 - Set up mail server
- "Delete Users" interface on management page -> move to deleted accounts table
- Clean up all files and folders on deleting art

# Features
- Invites - full CRUD
- Button to view all subfolders
- Search system
- More complex tag filtering
- Manage artworks page
- Moderator edit artwork page
- Reorder artwork images without reuploading (an additional input value?)
- Make component that renders usernames with flair
- List the contents of each folder inside its management page.
- Buttons to view all subfolders
- Upload banner image

# QOL / Aesthetic
- Reorder folders, artworks, and other stuff -- drag and drop reordering
- Thumbnail cropping
- Validate that every artwork has either text or an image.
- Move FolderListService into Folder model
- Add links to upload to folder & edit folder from within folder


# Meta
- Discuss how to handle users who close their accounts. Do we want to keep their data around for archival? Should we do cleanup at all?