Deployment steps for InfinityFree

1. Build assets locally

```bash
npm install
npm run build
```

2. Prepare `.env` on your local machine by copying `.env.example.production` and filling real values (DB host, user, password, APP_URL).

3. Ensure `public/storage` contains uploaded files. If you have uploads in `storage/app/public`, run locally:

```bash
php artisan storage:move-public
```

4. Upload to InfinityFree via FTP (FileZilla recommended):
   - Connect using your FTP credentials from InfinityFree control panel.
   - Upload all files and folders inside the project `public` directory to the remote `htdocs` (or the public folder used by InfinityFree).
   - Also upload `vendor` and other required files if you are deploying without Composer on server.

5. If you cannot run Composer on the host, run `composer install --no-dev --optimize-autoloader` locally and upload the `vendor` directory.

6. Ensure `public/storage` and its subfolders are uploaded and readable.

7. On InfinityFree, ensure the domain points to the `htdocs` folder and `APP_URL` in `.env` matches your domain.

8. Test uploading a banner/product via the admin UI. If uploads fail, check PHP error logs and file permissions.

Notes
- InfinityFree may not allow creating symbolic links; we configured the app to save files directly into `public/storage` so uploads should be web-accessible.
- If your hosting supports SSH and Composer, you can instead upload source and run `composer install` and `php artisan storage:link` on the server.
