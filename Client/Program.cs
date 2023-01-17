using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;
using System.IO;

namespace Client
{
    internal class Program
    {
        static void Main()
        {
            string[] settings = File.ReadAllText("settings.ini").Split('\n'); //reading the settings and splintting the lines of the file
            int refreshT = 60000;//Default wait time between ip checks (1 minute)
            if (settings.Length <= 0)//checking the length of the settings file
            {
                Console.WriteLine("Settings file not found or empty\nDefaulting for 5 mintues as ip webclient refresh time");
            }
            else
            {
                try
                {
                    refreshT = int.Parse(settings[0]); //parsing string from settings to int to get the refresh threshold
                    refreshT *= 60000; //converting minutes to milliseconds

                }
                catch (Exception ex)
                {
                    Console.WriteLine(ex.Message);
                }
            }
            string mName = System.Environment.MachineName; //get machine name | which with the username would be used to identify the client
            string uName = System.Environment.UserName;    //get username
            
            string url = settings[2] +  mName + "_" + uName; //URL of the server in the settings file + machine name and username sperated by underscore
            WebClient webclient = new WebClient(); //webclient object we'll use to connect to the ip server and our server
            string ipRequest="";
            while (ipRequest == "") //looping if the ip server is unreachable
            {
                try
                {
                    ipRequest = webclient.DownloadString("https://checkip.amazonaws.com/").Trim();//getting our ip, you can use any public ip reporting api
                }
                catch (Exception ex)
                {
                    Console.WriteLine(ex.Message);
                }
                if (ipRequest.Length == 0)
                {
                    System.Threading.Thread.Sleep(30000);//sleeping for half a minute before retrying to reestablish connection
                }
            }
            string servupdate = "";
            while (servupdate != "Success") //looping if our server is unreachable
            { 
                try
                    {
                        servupdate = webclient.DownloadString(url); //Initial Connection to the server
                    }
                catch (Exception ex)
                    {
                        Console.WriteLine(ex.Message);
                    }
                if (servupdate != "Success")
                {
                    System.Threading.Thread.Sleep(30000);//sleeping for half a minute before retrying
                }
            }

            if (servupdate == "Success")
            {
                Console.WriteLine("Connection Established");
            }
                string currIP = ipRequest; //by this point we should have an ip
                
                //main loop of the program

                while (true) //looping to check for ip change
            {

                    System.Threading.Thread.Sleep(refreshT); //waiting the specified threshold
                try
                {
                    ipRequest = webclient.DownloadString("https://checkip.amazonaws.com/").Trim(); //getting an ip
                }
                catch (Exception ex)
                { 
                Console.WriteLine(ex.Message);
                }
                    if (currIP.Equals(ipRequest)) //checking for ip change
                    {
                        Console.WriteLine("No IP change");
                        continue;
                    }
                    else //ip changed
                    {
                        currIP = ipRequest; //setting up the values for the next loop
                    if (webclient.DownloadString(url).Equals("Success")) //connecting to the server and checking the response
                    {
                        Console.WriteLine("IP updated Successfully");
                        
                    }
                    else
                    {
                        Console.WriteLine("IP update failure");
                    }
                        
                    }


                }

        }
    }
}
