import re, pathlib
root = pathlib.Path('.')
patterns = [r'SELECT', r'INSERT INTO', r'UPDATE', r'DELETE FROM', r'CALL', r'ALTER TABLE', r'CREATE TABLE', r'SELECT \*']
regex = re.compile('|'.join(patterns), re.IGNORECASE)
files = sorted(list(root.rglob('*.php')) + list(root.rglob('*.sql')), key=lambda p: str(p).lower())
seen = set()
for f in files:
    text = f.read_text(encoding='utf-8', errors='ignore')
    lines = text.splitlines()
    for i, line in enumerate(lines):
        if regex.search(line):
            if f.suffix.lower() == '.sql':
                key = (str(f), i+1, line.strip())
                if key not in seen:
                    print(f'{key[0]}:{key[1]}: {key[2]}')
                    seen.add(key)
                continue
            if '"' in line or "'" in line:
                m = re.search(r'([\"\'])(.*?\b(?:SELECT|INSERT INTO|UPDATE|DELETE FROM|CALL|ALTER TABLE|CREATE TABLE)\b.*)', line, re.IGNORECASE)
                if m:
                    quote = m.group(1)
                    content = m.group(2)
                    if content.count(quote) >= 1 and content.rstrip().endswith(quote):
                        key = (str(f), i+1, content.strip())
                        if key not in seen:
                            print(f'{key[0]}:{key[1]}: {key[2]}')
                            seen.add(key)
                    else:
                        buf = [content.strip()]
                        for j in range(i+1, len(lines)):
                            buf.append(lines[j].strip())
                            if quote in lines[j]:
                                break
                        q = '\n'.join(buf)
                        key = (str(f), i+1, q)
                        if key not in seen:
                            print(f'{key[0]}:{key[1]}: {key[2]}')
                            seen.add(key)
                else:
                    key = (str(f), i+1, line.strip())
                    if key not in seen:
                        print(f'{key[0]}:{key[1]}: {key[2]}')
                        seen.add(key)
            else:
                key = (str(f), i+1, line.strip())
                if key not in seen:
                    print(f'{key[0]}:{key[1]}: {key[2]}')
                    seen.add(key)
