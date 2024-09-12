<?php

namespace App\Controller;

use App\JwtTool;
use App\HttpTools;
use Google\Client;
use GuzzleHttp\Client as GuzzleHttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GoogleCalDAVController extends AbstractController
{

   private string $state;

   public function __construct()
   {
      $this->state = md5(time() . '@ginov');
   }

   /* #[Route('/google__', name: 'google_code__', methods: ['GET'])]
   public function index(): JsonResponse
   {
      $url = "https://accounts.google.com/o/oauth2/v2/auth?scope=" .
         $this->getParameter('google.scope') . "&access_type=offline&include_granted_scopes=true&response_type=code&redirect_uri=" .
         $this->getParameter('google.redirect.uri') . "&client_id=" .
         $this->getParameter('google.client.id');

      return $this->json([
         'url' => urldecode($url),
         'message' => 'In your browser go to url above'
      ], Response::HTTP_OK);
   }

   #[Route('/oauth2callback__.php', name: 'google_callback__', methods: ['GET'])]
   public function callback(Request $request): JsonResponse
   {
      $json = (new HttpTools('https://oauth2.googleapis.com'))
         ->post('/token', [
            'code' => $request->query->get('code'),
            'client_id' => $this->getParameter('google.client.id'),
            'client_secret' => $this->getParameter('google.client.secret'),
            'redirect_uri' => $this->getParameter('google.redirect.uri'),
            'grant_type' => 'authorization_code',
         ], ['Content-Type' => 'application/x-www-form-urlencoded'])
         ->json();

      return $this->json($json);
   } */

   #[Route('/google/login__', name: 'google_login__', methods: ['POST'])]
   public function login(Request $request): JsonResponse
   {
      $user = (new \App\Security\User())
         ->setUsername('goolge')
         ->setPassword($request->request->get('token'))
         ->setCalCollectionName('google');

      $jwt = JwtTool::encode($this->getParameter('jwt.api.key'), $user);

      // Get all calandars on server
      $calendars = (new HttpTools('https://www.googleapis.com/calendar/v3/'))
         ->get('users/me/calendarList', [], [
            'Authorization' => "Bearer " . $user->getPassword()
         ])
         ->json();

      return $this->json([
         'token' => $jwt,
         'calendars' => $calendars
      ], Response::HTTP_OK);
   }

   #[IsGranted('ROLE_USER', message: 'Acces denied', statusCode: Response::HTTP_UNAUTHORIZED)]
   #[Route('/google/calendars', name: 'google_calendars', methods: ['GET'])]
   public function calendars(): JsonResponse
   {
      /** @var App\Security\User */
      $user = $this->getUser();

      $calendars = (new HttpTools('https://www.googleapis.com/calendar/v3/'))
         ->get('users/me/calendarList', [], [
            'Authorization' => "Bearer " . $user->getPassword()
         ])
         ->json();

      return $this->json(['calendar' => $calendars], Response::HTTP_OK);
   }

   #[IsGranted('ROLE_USER', message: 'Acces denied', statusCode: Response::HTTP_UNAUTHORIZED)]
   #[Route('/google/events/{calID}', name: 'google_events', methods: ['GET'])]
   public function getEvents(string $calID): JsonResponse
   {
      /** @var App\Security\User */
      $user = $this->getUser();

      /* $http = (new GuzzleHttpClient([
         'base_uri' => "https://apidata.googleusercontent.com/caldav/v2/$calID/events",
         'verify' => $this->getParameter('certificate.path')
      ]))->request('GET', '',  ['headers' => ['Authorization' => "Bearer " . $user->getPassword()]]); 
      $events = $http->getBody();*/

      $events = (new HttpTools($this->getParameter('google.caldav.url'), $this->getParameter('certificate.path')))
         ->get("calendars/$calID/events", [], ['Authorization' => "Bearer " . $user->getPassword()])
         ->brut();

      dd($events);

      return $this->json(['events' => $events], Response::HTTP_OK);
   }
}
