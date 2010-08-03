/* Coded by ClaudeNegm */
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Xml;
using System.IO;
using System.Globalization;
using System.Text.RegularExpressions;
using System.Reflection;
using System.Windows.Forms;
using System.Collections;
using System.Diagnostics;
using Armageddon_WDB_Converter.Converter;

namespace Armageddon_WDB_Converter
{
    class Program
    {
        static XmlDocument structure_WDB = new XmlDocument();
        static void Main(string[] args)
        {
            Console.WriteLine("Welcome to MaNGOS_WDB v" + Application.ProductVersion + "!");
            try
            {
                Console.Clear();
                string SQL_Path = Application.StartupPath + @"\SQL\";
                string WDB_Path = Application.StartupPath + @"\WDB\";
                string Definitions_Path = Application.StartupPath + @"\definitions.xml";

                structure_WDB.Load(Definitions_Path);
                Console.WriteLine("Structures Loaded!\n");

                string[] directories = Directory.GetDirectories(WDB_Path);
                if (((directories.Length == 1) && (directories[0].ToLower().EndsWith(".svn"))) || (directories.Length == 0))
                {
                    new WDB_Parser(structure_WDB, WDB_Path, SQL_Path);
                }
                else // Support multiple folders :) and skip anything in the main folder
                {
                    foreach (string x in directories)
                    {
                        if (!x.ToLower().EndsWith(".svn"))
                        {
                            SQL_Path = Application.StartupPath + x.Replace(Application.StartupPath, "").Replace("\\WDB\\", "\\SQL\\") + "\\";
                            Directory.CreateDirectory(SQL_Path);

                            new WDB_Parser(structure_WDB, x, SQL_Path);
                        }
                    }
                }

                Console.WriteLine("Done, thank you for using MaNGOS_WDB!\nPorted by Singlem\nThanks to ClaudeNegm for orginal code;)\n\nPress enter to exit.");
            }
            catch (Exception ex)
            {
                Console.Write(ex.Message);
            }

        }
    }
}
/* Coded by ClaudeNegm */