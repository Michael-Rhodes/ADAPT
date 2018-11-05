import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AppComponent } from './app.component';
import { LogsComponent } from './logs/logs.component';
import { AboutComponent } from './about/about.component';

const routes: Routes = [
  { path: 'about', component:AboutComponent},
  { path: 'logs', component: LogsComponent},
  {path: '**', redirectTo: '/about'}
]; 

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})

export class AppRoutingModule { }
