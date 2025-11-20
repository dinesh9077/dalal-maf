# GitHub Actions Deployment Guide for cPanel (SSH/RSYNC)

This guide explains how to set up automatic deployment to your cPanel hosting using GitHub Actions with SSH and rsync.

## Prerequisites

1. GitHub repository with your Laravel project
2. cPanel hosting account with SSH access enabled
3. Access to GitHub repository settings
4. SSH key pair (we'll generate this)

## Setup Instructions

### Step 1: Generate SSH Key Pair

On your local machine, generate an SSH key pair:

```bash
ssh-keygen -t rsa -b 4096 -C "github-actions-deploy" -f ~/.ssh/github_deploy_key
```

This creates two files:
- `github_deploy_key` (private key - keep this secret!)
- `github_deploy_key.pub` (public key)

### Step 2: Add Public Key to cPanel   

1. Log in to your cPanel
2. Go to **Security** → **SSH Access** → **Manage SSH Keys**
3. Click **Import Key**
4. Paste the contents of `github_deploy_key.pub`
5. Click **Import**
6. Click **Manage** → **Authorize** to authorize the key

**Alternative method via command line:**
```bash
# Copy the public key to your server
ssh-copy-id -i ~/.ssh/github_deploy_key.pub username@your-server.com

# Or manually add it to authorized_keys
cat ~/.ssh/github_deploy_key.pub | ssh username@your-server.com "mkdir -p ~/.ssh && cat >> ~/.ssh/authorized_keys"
```

### Step 3: Configure GitHub Secrets

Go to your GitHub repository → Settings → Secrets and variables → Actions → New repository secret

Add the following secrets:

#### SSH Deployment Secrets (Required)
- **SSH_PRIVATE_KEY**: Contents of your `github_deploy_key` file (the entire private key including `-----BEGIN` and `-----END` lines)
- **CPANEL_HOST**: Your server hostname or IP (e.g., "server123.yourdomain.com" or "123.45.67.89")
- **CPANEL_USERNAME**: Your cPanel/SSH username
- **REMOTE_PATH**: Full path to your project (e.g., "/home/username/public_html" or "/home/username/domains/yourdomain.com/public_html")
- **SSH_PORT**: SSH port (usually "22", or check with your host - some use "21098" or other ports)

### Step 4: Prepare Your cPanel

1. **Create Database**: Create a MySQL database and user in cPanel
2. **Create .env file**: Upload or create your `.env` file on the server with production credentials
3. **Set Permissions**: Ensure the following directories are writable:
   - `storage/`
   - `bootstrap/cache/`
4. **Document Root**: Point your domain to the `public` folder

### Step 5: Test SSH Connection

Before deploying, test your SSH connection:

```bash
ssh -i ~/.ssh/github_deploy_key -p 22 username@your-server.com
```

If successful, you should be logged into your server.

### Step 6: Initial Deployment

1. Push your code to the `main` branch:
   ```bash
   git add .
   git commit -m "Setup deployment"
   git push origin main
   ```

2. Go to GitHub → Actions tab to monitor the deployment

### Step 7: Post-Deployment (Manual - First Time Only)

After the first deployment, SSH into your server and run:

```bash
cd /path/to/your/project
php artisan key:generate  # Only if APP_KEY is not set in .env
php artisan storage:link
```

## Workflow Triggers

The deployment will automatically run when:
- You push to the `main` branch
- You manually trigger it from GitHub Actions tab (workflow_dispatch)

## What Gets Deployed

The workflow will:
1. ✅ Checkout your code from GitHub
2. ✅ Install Composer dependencies (production only)
3. ✅ Upload files via rsync (fast, only changed files)
4. ✅ Preserve your `.env` file on the server (won't be overwritten)
5. ✅ Set correct file permissions
6. ✅ Run Laravel optimization commands (config, route, view cache)
7. ✅ Run database migrations

## What Gets Excluded

The following files/folders are NOT uploaded:
- `.git` and `.github` folders
- `node_modules`
- `tests`
- `.env` and `.env.example` (your server .env is preserved)
- `storage/logs/*` (preserves your production logs)
- `storage/framework/sessions/*` (preserves active sessions)
- `storage/framework/views/*` (will be regenerated)
- `storage/framework/cache/*` (will be regenerated)

## Troubleshooting

### SSH Connection Issues
**Problem**: "Permission denied (publickey)"
- Verify SSH key is added to cPanel authorized_keys
- Check SSH_PRIVATE_KEY secret contains the full private key
- Ensure SSH port is correct (try 22, 21098, or check with your host)
- Test connection manually: `ssh -i ~/.ssh/github_deploy_key username@host`

**Problem**: "Host key verification failed"
- This is handled by `StrictHostKeyChecking=no` in the workflow
- If still failing, try connecting manually once to add host to known_hosts

### Permission Errors
**Problem**: "Permission denied" when setting permissions
- Ensure your SSH user has write access to the deployment directory
- Check if you need to use `sudo` (not recommended for cPanel)
- Verify REMOTE_PATH is correct

**Problem**: "500 Internal Server Error" after deployment
- Check storage and bootstrap/cache are writable: `chmod -R 775 storage bootstrap/cache`
- Verify .env file exists and has correct values
- Check Laravel logs: `storage/logs/laravel.log`

### Rsync Issues
**Problem**: "rsync: command not found"
- Rsync should be available on most Linux servers
- Contact your hosting provider if not available

**Problem**: Files not syncing
- Check REMOTE_PATH is correct
- Ensure you have enough disk space on server
- Verify SSH connection works

### Laravel Issues
**Problem**: "Class not found" errors
- Run `composer dump-autoload` on server
- Clear all caches: `php artisan cache:clear && php artisan config:clear`

**Problem**: Database migration fails
- Check database credentials in .env
- Ensure database user has migration privileges
- Run migrations manually: `php artisan migrate --force`

## Alternative: Using SFTP Instead of FTP

If your host supports SFTP, modify the deploy.yml file:

```yaml
- name: Deploy to cPanel via SFTP
  uses: wlixcc/SFTP-Deploy-Action@v1.2.4
  with:
    server: ${{ secrets.FTP_SERVER }}
    username: ${{ secrets.FTP_USERNAME }}
    password: ${{ secrets.FTP_PASSWORD }}
    port: 22
    local_path: './*'
    remote_path: ${{ secrets.FTP_SERVER_DIR }}
    sftp_only: true
```

## Security Best Practices

1. **Never commit .env file** to repository
2. **Use strong passwords** for all secrets
3. **Limit FTP user permissions** to only necessary directories
4. **Enable 2FA** on GitHub account
5. **Regularly rotate** FTP/SSH passwords

## Manual Deployment (Fallback)

If GitHub Actions fails, you can deploy manually:

1. Download repository as ZIP from GitHub
2. Upload via cPanel File Manager or FTP client
3. Extract files to public_html
4. Update .env file with production credentials
5. Run `composer install --no-dev` via SSH/Terminal

## Support

For issues with:
- **GitHub Actions**: Check Actions tab for error logs
- **cPanel**: Contact your hosting provider
- **Laravel**: Check Laravel logs in `storage/logs/`

## Notes

- The workflow excludes `.git`, `node_modules`, and `tests` from deployment
- Composer dependencies are installed with `--no-dev` flag for production
- Laravel caches are cleared and rebuilt on each deployment
- Storage logs are excluded to prevent overwriting production logs
