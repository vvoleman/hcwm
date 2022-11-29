# Download the file
import urllib.request
urllib.request.urlretrieve('https://argosopentech.nyc3.digitaloceanspaces.com/argospm/translate-cs_en-1_5.argosmodel', 'translate-cs_en-1_5.argosmodel')

# Install it
from argostranslate import package
package.install_from_path('translate-cs_en-1_5.argosmodel')