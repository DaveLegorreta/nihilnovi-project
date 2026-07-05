import re

with open('data/nihilnovi_articles_import.xml', 'r', encoding='utf-8') as f:
    content = f.read()

# Define subcategories to add
subcategories = [
    ('presocraticos', 'Presocráticos', 'filosofia'),
    ('clasicos', 'Clásicos', 'filosofia'),
    ('metafisica', 'Metafísica', 'filosofia'),
    ('etica', 'Ética', 'filosofia'),
    ('politica', 'Política', 'filosofia'),
    ('epistemologia', 'Epistemología', 'filosofia'),
    ('logica', 'Lógica', 'filosofia'),
    ('estetica', 'Estética', 'filosofia'),
]

# Build category XML
subcat_xml = ''
for nicename, cat_name, parent in subcategories:
    subcat_xml += f'  <wp:category><wp:category_nicename>{nicename}</wp:category_nicename><wp:cat_name><![CDATA[{cat_name}]]></wp:cat_name><wp:category_parent>{parent}</wp:category_parent></wp:category>\n'

# Insert after the last existing wp:category
last_cat_pattern = r'(  <wp:category>.*?</wp:category>\n)'
last_cat_match = None
for m in re.finditer(last_cat_pattern, content):
    last_cat_match = m

if last_cat_match:
    insert_pos = last_cat_match.end()
    content = content[:insert_pos] + subcat_xml + content[insert_pos:]
    print(f'Inserted {len(subcategories)} subcategories after existing categories')
else:
    print('No existing categories found')

# Save
with open('data/nihilnovi_articles_import.xml', 'w', encoding='utf-8') as f:
    f.write(content)

print('File saved successfully')
