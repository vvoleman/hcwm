class ParamRetriever:
    def __init__(self, request):
        self.request = request

    def validate(self, params):
        results = {}
        for param in params:
            value = self.request.args.get(param, None)
            if value is None:
                return None
            results[param] = value
        return results

    def get(self, param):
        return self.request.args.get(param, None)

    def get_all(self):
        return self.request.args