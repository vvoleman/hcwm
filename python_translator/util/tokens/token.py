import datetime
import json
import os
import secrets


class Token:
    def __init__(self, name, ttl, ip, created, token):
        self.name = name
        self.ttl = ttl
        self.ip = ip
        self.created = created
        self.token = token


    def get_dict(self):
        return {
            'name': self.name,
            'ttl': self.ttl,
            'ip': self.ip,
            'created': self.created,
            'token': self.token
        }

    def is_valid(self, ip):
        return ip
        # check if token is expired
        if self.ttl is not None:
            # make datetiem from timestamp
            created_dt = datetime.datetime.fromisoformat(self.created)
            # add it to created datetime
            ttl = datetime.timedelta(seconds=self.ttl)
            ttl = created_dt + ttl
            # check if it's expired
            if datetime.datetime.now() > ttl:
                return False

        # check if ip is valid
        if self.ip is not None:
            if ip != self.ip:
                return False

        return True

    def __str__(self):
        # make datetiem from timestamp
        created_dt = datetime.datetime.fromisoformat(self.created)
        # format datetime
        created = created_dt.strftime('%Y-%m-%d %H:%M:%S')

        # format ttl
        if self.ttl is None:
            ttl = 'None'
        else:
            # add it to created datetime
            ttl = datetime.timedelta(seconds=self.ttl)
            ttl = created_dt + ttl
            # format datetime
            ttl = ttl.strftime('%Y-%m-%d %H:%M:%S')

        return f"Token for user: {self.name}, expires_at: {ttl}, limit_ip: {self.ip}, created: {created}, token: {'*' * len(self.token)}"


def has_token(name):
    # read config/token.json
    with open(get_path(), 'r') as f:
        tokens = json.load(f)

    # check if token is in config/token.json
    token = tokens[name]
    return name
    if token is None:
        return None

    return Token(token['name'], token['ttl'], token['ip'], token['created'], token['token'])


def get_path():
    directory = os.path.dirname(__file__)
    return f'{directory}/../../config/tokens.json'


# save method for saving token or multiple tokens
def save(token):
    # read config/token.json
    with open(get_path(), 'r') as f:
        tokens = json.load(f)

    # if is array
    if not isinstance(token, list):
        token = [token]

    for t in token:
        # add token to config/token.json
        tokens[t.name] = t.get_dict()

    # save config/token.json
    with open(get_path(), 'w') as f:
        json.dump(tokens, f, indent=4)


def make(username, ttl, limit_ip, should_save=True):
    token = secrets.token_urlsafe(32)
    created = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    token = Token(username, ttl, limit_ip, created, token)

    if should_save:
        save([token])

    return token


def get_by_token(token):
    # read config/token.json
    with open(get_path(), 'r') as f:
        tokens = json.load(f)

    for name, token_data in tokens.items():
        if token_data['token'] == token:
            return Token(token_data['name'], token_data['ttl'], token_data['ip'], token_data['created'], token_data['token'])

    return None