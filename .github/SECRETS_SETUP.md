# GitHub Secrets Quick Setup Guide

## Required Secrets for SSH Deployment

Go to: **GitHub Repository** → **Settings** → **Secrets and variables** → **Actions** → **New repository secret**

### 1. SSH_PRIVATE_KEY
**Description**: Your private SSH key for authentication

**How to get it**:
```bash
# Generate new key
ssh-keygen -t rsa -b 4096 -C "github-actions" -f ~/.ssh/github_deploy_key

# Display private key (copy ALL of this including BEGIN/END lines)
cat ~/.ssh/github_deploy_key
```

**Value format**:
```
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAABlwAAAAdzc2gtcn
... (many lines) ...
-----END OPENSSH PRIVATE KEY-----
```

---

### 2. CPANEL_HOST
**Description**: Your server hostname or IP address

**Examples**:
- `server123.yourdomain.com`
- `123.45.67.89`
- `cpanel.yourhost.com`

**How to find it**:
- Check your hosting welcome email
- Look in cPanel under "Server Information"
- Contact your hosting provider

---

### 3. CPANEL_USERNAME
**Description**: Your cPanel/SSH username

**Examples**:
- `username`
- `cpanel_user`

**How to find it**:
- Check your hosting welcome email
- Look in cPanel top-right corner
- Same as your cPanel login username

---

### 4. REMOTE_PATH
**Description**: Full path to your project directory on the server

**Examples**:
- `/home/username/public_html`
- `/home/username/domains/yourdomain.com/public_html`
- `/home/username/www`

**How to find it**:
```bash
# SSH into your server and run:
pwd
# This shows your current directory path
```

**Important**: 
- Must be absolute path (start with `/`)
- Should NOT end with trailing slash
- Point to where your Laravel files should be (where `artisan` file is located)

---

### 5. SSH_PORT (Optional)
**Description**: SSH port number

**Default**: `22`

**Common alternatives**:
- `22` (standard SSH port)
- `21098` (common cPanel SSH port)
- `2222` (alternative port)

**How to find it**:
- Check your hosting documentation
- Try connecting: `ssh -p 22 username@host`
- Contact your hosting provider

**Note**: If not set, defaults to port 22

---

## How to Add Public Key to Server

After generating your SSH key, add the public key to your server:

### Method 1: Via cPanel
1. Login to cPanel
2. Go to **Security** → **SSH Access**
3. Click **Manage SSH Keys**
4. Click **Import Key**
5. Paste contents of `~/.ssh/github_deploy_key.pub`
6. Click **Import**
7. Find the key and click **Manage** → **Authorize**

### Method 2: Via Command Line
```bash
# Copy public key to server
ssh-copy-id -i ~/.ssh/github_deploy_key.pub username@your-server.com

# Or manually
cat ~/.ssh/github_deploy_key.pub | ssh username@your-server.com "mkdir -p ~/.ssh && chmod 700 ~/.ssh && cat >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys"
```

### Method 3: Manual Upload
1. Display your public key:
   ```bash
   cat ~/.ssh/github_deploy_key.pub
   ```
2. SSH into your server
3. Edit authorized_keys:
   ```bash
   nano ~/.ssh/authorized_keys
   ```
4. Paste the public key on a new line
5. Save and exit (Ctrl+X, Y, Enter)
6. Set permissions:
   ```bash
   chmod 700 ~/.ssh
   chmod 600 ~/.ssh/authorized_keys
   ```

---

## Testing Your Setup

### Test SSH Connection
```bash
ssh -i ~/.ssh/github_deploy_key -p 22 username@your-server.com
```

If successful, you should be logged into your server.

### Test from GitHub Actions
1. Go to your repository on GitHub
2. Click **Actions** tab
3. Click **Deploy to cPanel via SSH** workflow
4. Click **Run workflow** → **Run workflow**
5. Monitor the deployment progress

---

## Common Issues

### "Permission denied (publickey)"
- ✅ Public key is added to server's authorized_keys
- ✅ Private key is correctly added to GitHub secrets
- ✅ SSH port is correct

### "Host key verification failed"
- This should be handled automatically by the workflow
- If persists, try connecting manually once

### "rsync: command not found"
- Contact your hosting provider
- Rsync should be available on most Linux servers

---

## Security Notes

⚠️ **NEVER** commit your private key to the repository
⚠️ **NEVER** share your private key
✅ **ALWAYS** use GitHub Secrets for sensitive data
✅ **ALWAYS** use different keys for different purposes
✅ **REGULARLY** rotate your SSH keys

---

## Quick Checklist

Before deploying, ensure:
- [ ] SSH key pair generated
- [ ] Public key added to server
- [ ] All 4-5 secrets added to GitHub
- [ ] .env file exists on server
- [ ] Database created on server
- [ ] Tested SSH connection manually
- [ ] Pushed code to main branch

---

## Need Help?

1. Check GitHub Actions logs for detailed error messages
2. Test SSH connection manually
3. Verify all secrets are correctly set
4. Check server logs: `tail -f storage/logs/laravel.log`
5. Contact your hosting provider for SSH access issues
