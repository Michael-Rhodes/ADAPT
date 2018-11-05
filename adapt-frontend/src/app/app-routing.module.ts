import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AppComponent } from './app.component';
import { LogsComponent } from './logs/logs.component';

const routes: Routes = [
  { path: 'home', component: AppComponent },
  { path: 'logs', component: LogsComponent},
  {path: '**', redirectTo: '/home'}
]; 

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})

export class AppRoutingModule { }
