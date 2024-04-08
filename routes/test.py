import os

directorio_actual = os.getcwd()
print(f"Estás en el directorio actual: {directorio_actual}")


nombre_archivo = "c:/Users/mjomiak/Desktop/sigma/routes/web.php"

# Línea que deseas agregar
linea_a_agregar = "Esta es la línea que quiero agregar."

# Abre el archivo en modo de escritura (modo "a" para agregar al final)
try:
    # Abre el archivo en modo de escritura (modo "a" para agregar al final)
    with open(nombre_archivo, "a") as archivo:
        archivo.write(linea_a_agregar + "\n")
    print(f"Se agregó la línea al archivo: {nombre_archivo}")
except IOError as e:
    print(f"Error al escribir en el archivo: {e}")