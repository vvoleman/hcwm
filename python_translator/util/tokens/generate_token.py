import ipaddress
from token import make, has_token


def generate_token(username, ttl, limit_ip):
    token = make(username, ttl, limit_ip)
    print(token)


if __name__ == '__main__':
    # Prompt user for username to generate token for
    user_name = input('Enter username for token: ')

    existing = has_token(user_name)
    if existing is not None:
        print(f'User {user_name} already has a token: {existing}')
        exit(1)

    ip = input('Enter IP address to limit token to (leave blank for no limit): ')
    if ip == '':
        ip = None

    # Prompt user for token TTL
    ttl = input('Enter token TTL (leave blank for no limit, add suffix for unit, eg 3600s): ')
    if ttl == '':
        ttl = None
    else:
        # check if ttl has suffix
        multipliers = {'s': 1, 'm': 60, 'h': 3600, 'd': 86400, 'y': 31536000}
        if ttl[-1] not in multipliers:
            print(f"Invalid TTL, allowed suffixes are: {','.join(multipliers.keys())}")
            exit(1)
        # recalculate ttl to seconds
        ttl = int(ttl[:-1]) * multipliers[ttl[-1]]
    # Generate token
    generate_token(user_name, ttl, ip)
