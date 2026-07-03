import sys
import urllib.request
from pathlib import Path

def reporthook(blocknum, blocksize, totalsize):
    readsofar = blocknum * blocksize
    if totalsize > 0:
        percent = min(100.0, readsofar * 100 / totalsize)
        s = f"\r[+] Descargando: {percent:.1f}% ({readsofar / (1024*1024):.2f} MB de {totalsize / (1024*1024):.2f} MB)"
        sys.stdout.write(s)
        sys.stdout.flush()
    else:
        sys.stdout.write(f"\r[+] Descargando: {readsofar / (1024*1024):.2f} MB")
        sys.stdout.flush()

def download(url, dest_path):
    dest = Path(dest_path)
    if dest.exists():
        print(f"[*] Borrando archivo existente corrupto o incompleto de {dest.stat().st_size / (1024*1024):.2f} MB")
        dest.unlink()
        
    print(f"[*] Iniciando descarga desde {url}")
    print(f"[*] Guardando en {dest.absolute()}")
    try:
        urllib.request.urlretrieve(url, dest, reporthook)
        print("\n[+] Descarga completada con éxito!")
    except Exception as e:
        print(f"\n[!] Error en la descarga: {e}")
        if dest.exists():
            dest.unlink()

if __name__ == "__main__":
    url = "https://dn790000.ca.archive.org/0/items/ColeccionObrasGrecoLatinas3/WernerJeger-Paideia.LosIdealesDeLaCulturaGriegalibrosI-ii-iiiYIv_text.pdf"
    dest = r"c:\Users\david\OneDrive\Documentos\Skills\Projecto MKT\data\pdfs\WernerJeger-Paideia.LosIdealesDeLaCulturaGriegalibrosI-ii-iiiYIv_text.pdf"
    download(url, dest)
