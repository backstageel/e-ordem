# Multi-Factor Authentication (MFA) Administrator Guide

This document provides information for administrators about the Multi-Factor Authentication (MFA) implementation in the Ordem dos Médicos de Moçambique (OrMM) system.

## Overview

Multi-Factor Authentication (MFA) adds an additional layer of security to user accounts by requiring a second form of verification beyond just a password. In this implementation, we use Time-based One-Time Password (TOTP) authentication, which is compatible with apps like Google Authenticator, Microsoft Authenticator, and Authy.

## MFA Configuration

The MFA configuration is stored in `config/mfa.php` and includes the following settings:

- `enabled`: Whether MFA is enabled globally for the application (default: true)
- `required`: Whether MFA is required for all users or optional (default: false)
- `recovery_codes_count`: The number of recovery codes to generate for each user (default: 8)
- `recovery_code_length`: The length of each recovery code (default: 10)
- `window`: The window in which a TOTP code is valid, in seconds (default: 30)

You can modify these settings in the configuration file or by setting the corresponding environment variables in your `.env` file:

```
MFA_ENABLED=true
MFA_REQUIRED=false
```

## User Management

### Viewing MFA Status

Currently, there is no dedicated admin interface for viewing the MFA status of all users. However, you can check a user's MFA status in the database by looking at the `two_factor_enabled` column in the `users` table.

### Resetting MFA for a User

If a user loses access to their authenticator app and recovery codes, you can reset their MFA by disabling it in the database:

```sql
UPDATE users SET two_factor_secret = NULL, two_factor_recovery_codes = NULL, two_factor_enabled = false WHERE id = ?;
```

Replace `?` with the user's ID.

## Security Considerations

### Recovery Codes

Recovery codes are stored in the database as an encrypted array. Each code can only be used once, and after use, it is removed from the array.

### Secret Keys

The secret keys used for TOTP are stored in the database as encrypted values. They are never displayed to users after the initial setup.

### Session Management

MFA verification is required for each new session. If a user logs out or their session expires, they will need to verify MFA again when they log back in.

## Troubleshooting

### User Cannot Access Their Account

If a user cannot access their account due to MFA issues (lost device and recovery codes), you can:

1. Verify the user's identity through alternative means (e.g., email, phone, ID verification)
2. Reset their MFA as described above
3. Ask them to set up MFA again

### Time Synchronization Issues

TOTP relies on time synchronization between the server and the user's device. If users are consistently having issues with verification codes being rejected, check:

1. Server time synchronization
2. Advise users to ensure their device's time is set to automatic

### Database Issues

If there are issues with the MFA database columns, you may need to run the migration again:

```bash
sail artisan migrate:refresh --path=database/migrations/2025_07_29_201108_add_two_factor_columns_to_users_table.php
```

Note that this will reset all MFA settings for all users.

## Monitoring and Auditing

Currently, there is no dedicated logging for MFA events. Consider implementing additional logging for security-sensitive events such as:

- MFA enablement/disablement
- Failed MFA verification attempts
- Recovery code usage

## Future Enhancements

Consider the following enhancements to the MFA implementation:

1. Admin interface for managing users' MFA status
2. Dedicated logging for MFA-related events
3. Requiring MFA for sensitive operations (e.g., changing email, password)
4. Adding additional MFA methods (e.g., SMS, email)
5. Implementing remember-device functionality

## Support

For technical support with MFA implementation, contact the development team at [development@ordemdosmedicos.org.mz](mailto:development@ordemdosmedicos.org.mz).
