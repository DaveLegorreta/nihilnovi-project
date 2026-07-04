import os
import zipfile
from pathlib import Path

def create_theme_zip():
    base_dir = Path('c:/Users/david/OneDrive/Documentos/Skills/Projecto MKT')
    theme_dir = base_dir / 'nihilnovi-theme'
    output_zip = base_dir / 'nihilnovi-theme-v2.0.2.zip'
    
    if not theme_dir.exists():
        print(f"Error: no se encontró {theme_dir}")
        return
    
    with zipfile.ZipFile(output_zip, 'w', zipfile.ZIP_DEFLATED) as zf:
        for root, _, files in os.walk(theme_dir):
            for file in files:
                # Skip hidden files, backups, and zips
                if file.startswith('.') or file.endswith('~') or file.endswith('.zip'):
                    continue
                file_path = Path(root) / file
                arcname = str(file_path.relative_to(theme_dir))
                zf.write(file_path, arcname)
                print(f"  + {arcname}")
    
    size_mb = output_zip.stat().st_size / (1024 * 1024)
    print(f"\nZIP creado: {output_zip}")
    print(f"Tamaño: {size_mb:.2f} MB")
    print(f"Archivos: {len(zf.namelist())}")

if __name__ == '__main__':
    create_theme_zip()
