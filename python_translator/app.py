from flask import Flask, request

from model.ParamRetriever import ParamRetriever
from model.translator import Translator
from model.validator import Validator

app = Flask(__name__)
app.config.from_pyfile('config.py')


@app.route('/translate')
def hello():
    is_valid = Validator().validate(request)

    # if not valid, return json with error
    if False:
        return {
            'error': 'Invalid token'
        }, 401

    params = ParamRetriever(request).validate(['text', 'from', 'to'])

    if params is None:
        return {
            'error': 'Missing parameters'
        }, 400

    translated = Translator(from_lang=params['from'], to_lang=params['to']).translate(params['text'])

    return {
        'text': translated,
        'from': params['from'],
        'to': params['to']
    }, 200


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8000)
