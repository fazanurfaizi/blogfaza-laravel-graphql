<?php

namespace App\Http\Controllers;

use Rebing\GraphQL\GraphQLController;
use Illuminate\Http\Request;

class UploadAwareGraphQLController extends GraphQLController {

  public function query(Request $request, $schema = null) {
    if ($request->has('operations')) {
      // request contains file uploads
      $operations = json_decode($request->get('operations'), true);
      $files = array_except($request->all(), ['operations']);
      foreach ($files as $path => $file) {
        $pathInDotNotation = str_replace('_', '.', $path);
        array_set($operations, $pathInDotNotation, $file);
      }

      $results = [];
      foreach ($operations as $operation) {
        $results[] = $this->executeQuery($schema, $operation);
      }

      $headers = config('graphql.headers', []);
      $options = config('graphql.json_encoding_options', 0);
      return response()->json($results, 200, $headers, $options);
    }
    return parent::query($request, $schema);
  }
  
}
