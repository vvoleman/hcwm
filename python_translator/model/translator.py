from translate import Translator as TranslatorOrigin

class Translator:
    def __init__(self, from_lang, to_lang):
        self.from_lang = from_lang
        self.to_lang = to_lang
        self._refresh_translator()

    def _refresh_translator(self):
        self.translator = TranslatorOrigin(to_lang=self.to_lang, from_lang=self.from_lang)

    def set_from_lang(self, from_lang):
        self.from_lang = from_lang
        self._refresh_translator()

    def set_to_lang(self, to_lang):
        self.to_lang = to_lang
        self._refresh_translator()

    def translate(self, text, from_lang=None, to_lang=None):
        if from_lang is not None:
            self.set_from_lang(from_lang)
        if to_lang is not None:
            self.set_to_lang(to_lang)

        return self.translator.translate(text)
