import re


def extrae_pal(ora):
    ig = ['a']
    word = re.sub('[:$()[]', ' ', ora).split()
    word_er = [w.lower() for w in word if w not in ig]
    return word_er
