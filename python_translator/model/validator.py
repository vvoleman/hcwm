from util.tokens.token import has_token, get_by_token


class Validator:

    def validate(self, request):
        token = request.headers.get('Authorization', '')
        # Format should be: Bearer <token>
        if not token.startswith('Bearer '):
            return False
        token = token[7:]
        # Check if token is valid
        token = get_by_token(token)

        if token is None:
            return False

        # Check if token is valid
        ip = request.remote_addr
        if not token.is_valid(ip):
            return False

        return True