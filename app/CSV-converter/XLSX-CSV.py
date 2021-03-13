# Convertidor XLSX a CSV
# Autor: Yamel 'YMLabs' Mauge
# Version: 1.00
import numpy as np
import tkinter as tk
from tkinter import filedialog
from tkinter import messagebox
import pandas as pd

root = tk.Tk()

MainCanvas = tk.Canvas(root, width=300, height=300, bg='lightsteelblue2', relief='raised')
MainCanvas.pack()

etiqueta1 = tk.Label(root, text='Convertidor EXCEL a CSV. YMLabs V.1.05', bg='firebrick2' )
etiqueta1.config(font=('helvetica',10))
MainCanvas.create_window(150,60,window=etiqueta1)


def getExcel():
    global read_file

    import_file_path = filedialog.askopenfilename()
    read_file = pd.read_excel(import_file_path, header=0, converters={'SEGURO':str,'CEDULA_PASAPORTE':str, 'NOMBRE':str, 'PATRONO':str, 'RAZON_SO':str, 'TEL1':str,'TEL2':str,'FECHA':str} )
    read_file.SEGURO.fillna(value=0, inplace=True)

buscar_excel_boton = tk.Button(text="   Importar Archivo Excel    ", command=getExcel, bg="green", fg="white", font=('helvetica', 8, 'bold'))
MainCanvas.create_window(150,130, window=buscar_excel_boton)

def convertToCSV():
    global read_file

    export_file_path = filedialog.asksaveasfilename(defaultextension='.csv')
    read_file.to_csv(export_file_path, sep=';',index= False, header=True)

guardar_csvBtn = tk.Button(text="   Convertir Excel a CSV    ", command=convertToCSV, bg='blue', fg='white',font=('helvetica', 8, 'bold'))
MainCanvas.create_window(150,180, window=guardar_csvBtn)

def exitApp():
    msgbox = tk.messagebox.askquestion('Cerrar Aplicación', '¿Estás Seguro que quieres cerrar la aplicación?',icon='warning')
    if msgbox == 'yes':
        root.destroy()

exit_Btn =tk.Button(root,text="    Cerrar Aplicación    ", command = exitApp, bg='red', fg='white', font=('helvetica', 8, 'bold'))
MainCanvas.create_window(150,230, window=exit_Btn)
                                                                                                          
                   
root.mainloop()
